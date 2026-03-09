const fs = require('fs');
const path = require('path');

// Directory paths
const sourceFile = 'public/css/main.css';
const outputFile = 'public/build/css/custom.css';

function buildCSS() {
    let combinedCSS = '/* Combined Custom CSS - Generated automatically */\n\n';
    
    // Check if source file exists
    if (fs.existsSync(sourceFile)) {
        const content = fs.readFileSync(sourceFile, 'utf8');
        combinedCSS += `/* Compiled from ${sourceFile} */\n`;
        combinedCSS += content + '\n\n';
        
        // Ensure output directory exists
        const outputDir = path.dirname(outputFile);
        if (!fs.existsSync(outputDir)) {
            fs.mkdirSync(outputDir, { recursive: true });
        }
        
        // Write combined CSS
        fs.writeFileSync(outputFile, combinedCSS);
        console.log(`CSS built successfully: ${outputFile}`);
    } else {
        console.error(`Source file not found: ${sourceFile}`);
    }
}

// Run the build
buildCSS();

// Watch for changes in development
if (process.argv.includes('--watch')) {
    fs.watch(sourceFile, (eventType, filename) => {
        console.log(`CSS file changed: ${sourceFile}`);
        setTimeout(buildCSS, 100); // Debounce
    });
    console.log(`Watching for CSS changes on: ${sourceFile}`);
}