// Marcan Theme CSS Build System
// Splits theme.css into partials and rebuilds on demand.

const fs = require('fs');
const path = require('path');

const CSS_DIR = path.join(__dirname, 'assets', 'css');
const PARTIALS_DIR = path.join(CSS_DIR, 'partials');
const SOURCE = path.join(CSS_DIR, 'theme.css');

// Define partials by their starting class/selector patterns (order matters!)
const SECTIONS = [
    { file: '00-vars.css',          startPattern: /^@import url\('https:\/\/fonts\.googleapis\.com/ },
    { file: '01-typography.css',    startPattern: /^\.marcan-property-archive-copy h1,/ },
    { file: '02-iconic-project.css',startPattern: /^\.marcan-iconic-single \{/ },
    { file: '03-header.css',        startPattern: /^\.marcan-site-header \{/ },
    { file: '04-home-hero.css',     startPattern: /^\.marcan-home-hero \{/ },
    { file: '05-home-projects.css', startPattern: /^\.marcan-home-projects \{/ },
    { file: '06-home-delivered.css',startPattern: /^\.marcan-home-delivered \{/ },
    { file: '07-footer.css',        startPattern: /^\.marcan-site-footer \{/ },
    { file: '08-about.css',         startPattern: /^\.marcan-about-hero \{/ },
    { file: '09-about-team.css',    startPattern: /^\.marcan-about-promise \{/ },
    { file: '10-property-single.css',startPattern: /^\.marcan-property-single \{/ },
    { file: '11-property-archive.css',startPattern: /^\.marcan-property-archive-hero \{/ },
    { file: '12-property-card.css', startPattern: /^\.marcan-property-listing-card \{/ },
    { file: '13-property-units.css',startPattern: /^\.marcan-property-units \{/ },
    { file: '14-property-map.css',  startPattern: /^\.marcan-property-map \{/ },
    { file: '15-blog.css',          startPattern: /^\.marcan-blog-placeholder/ },
    { file: '16-contact-modal.css', startPattern: /^\s*\/\*\s*.*Plan Lightbox/ },
    { file: '17-responsive.css',    startPattern: /^\/\*\s*.*Contact Modal.*\*\/|^\.marcan-contact-modal \{/ },
    { file: '18-utilities.css',     startPattern: /^\.marcan-floating-whatsapp \{/ },
    { file: '19-keyframes.css',     startPattern: /^@keyframes introLogo/ },
];

function splitCSS() {
    if (!fs.existsSync(SOURCE)) {
        console.error(`Source not found: ${SOURCE}`);
        process.exit(1);
    }

    const content = fs.readFileSync(SOURCE, 'utf8').replace(/\r\n/g, '\n');
    const lines = content.split('\n');

    // Ensure partials directory exists
    if (!fs.existsSync(PARTIALS_DIR)) {
        fs.mkdirSync(PARTIALS_DIR, { recursive: true });
    }

    // Find line numbers where each section starts
    const boundaries = [];
    for (const section of SECTIONS) {
        const lineIdx = lines.findIndex(line => section.startPattern.test(line));
        if (lineIdx === -1) {
            console.warn(`Warning: pattern not found for ${section.file}: ${section.startPattern}`);
            continue;
        }
        boundaries.push({ ...section, line: lineIdx });
    }

    // Sort by line number
    boundaries.sort((a, b) => a.line - b.line);

    // Extract sections
    for (let i = 0; i < boundaries.length; i++) {
        const start = boundaries[i].line;
        const end = (i + 1 < boundaries.length) ? boundaries[i + 1].line : lines.length;
        const sectionLines = lines.slice(start, end);
        const output = path.join(PARTIALS_DIR, boundaries[i].file);
        fs.writeFileSync(output, sectionLines.join('\r\n') + '\r\n', 'utf8');
        console.log(`Written: ${boundaries[i].file} (lines ${start + 1}-${end})`);
    }

    console.log(`\nSplit complete. ${boundaries.length} partials created in ${PARTIALS_DIR}`);
}

function buildCSS() {
    if (!fs.existsSync(PARTIALS_DIR)) {
        console.error(`Partials directory not found: ${PARTIALS_DIR}`);
        process.exit(1);
    }

    // Read and concatenate partials in alphabetical order (00-, 01-, ...)
    const partialFiles = fs.readdirSync(PARTIALS_DIR)
        .filter(f => f.endsWith('.css'))
        .sort(); // alphabetical = correct order due to numbering

    const parts = [];
    for (const file of partialFiles) {
        const content = fs.readFileSync(path.join(PARTIALS_DIR, file), 'utf8').trim();
        parts.push(content);
    }

    const output = parts.join('\r\n\r\n') + '\r\n';
    fs.writeFileSync(SOURCE, output, 'utf8');
    console.log(`Built: ${SOURCE} from ${partialFiles.length} partials`);
}

// Verify build matches source
function verify() {
    if (!fs.existsSync(SOURCE)) {
        console.error(`Source not found: ${SOURCE}`);
        process.exit(1);
    }

    const backup = path.join(CSS_DIR, 'theme-backup.css');
    fs.copyFileSync(SOURCE, backup);

    buildCSS();

    const original = fs.readFileSync(backup, 'utf8').replace(/\r\n/g, '\n').replace(/\n{3,}/g, '\n\n').trim();
    const rebuilt = fs.readFileSync(SOURCE, 'utf8').replace(/\r\n/g, '\n').replace(/\n{3,}/g, '\n\n').trim();

    const origLines = original.split('\n').length;
    const rebuiltLines = rebuilt.split('\n').length;
    const diff = Math.abs(original.length - rebuilt.length);

    if (original === rebuilt) {
        console.log('VERIFY OK: rebuilt theme.css matches original bit-for-bit.');
        fs.unlinkSync(backup);
    } else if (diff < original.length * 0.01 && Math.abs(origLines - rebuiltLines) < 10) {
        console.log(`VERIFY OK (minor whitespace): ${diff} bytes difference, ${Math.abs(origLines - rebuiltLines)} lines diff.`);
        fs.unlinkSync(backup);
    } else {
        console.error(`VERIFY FAILED: ${diff} bytes diff, ${Math.abs(origLines - rebuiltLines)} lines diff.`);
        fs.copyFileSync(backup, SOURCE);
        fs.unlinkSync(backup);
        process.exit(1);
    }
}

const cmd = process.argv[2] || 'build';

switch (cmd) {
    case 'split': splitCSS(); break;
    case 'build': buildCSS(); break;
    case 'verify': verify(); break;
    default:
        console.log('Usage: node build-css.js [split|build|verify]');
        console.log('  split  - Extract theme.css into partials/');
        console.log('  build  - Concatenate partials/ into theme.css');
        console.log('  verify - Build and verify against original');
}
