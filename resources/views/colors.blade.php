<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Color Palette Generator</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #1a1a2e;
            color: #eee;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 10px;
            font-weight: 300;
            font-size: 2rem;
        }

        .subtitle {
            text-align: center;
            color: #888;
            margin-bottom: 40px;
        }

        .input-section {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            margin-bottom: 50px;
            flex-wrap: wrap;
        }

        .color-input-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #16213e;
            padding: 10px 20px;
            border-radius: 12px;
        }

        input[type="color"] {
            width: 50px;
            height: 50px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            background: none;
        }

        input[type="color"]::-webkit-color-swatch-wrapper {
            padding: 0;
        }

        input[type="color"]::-webkit-color-swatch {
            border: 2px solid #333;
            border-radius: 6px;
        }

        input[type="text"] {
            background: #0f0f23;
            border: 2px solid #333;
            color: #fff;
            padding: 12px 16px;
            font-size: 1.1rem;
            font-family: monospace;
            border-radius: 8px;
            width: 120px;
            text-transform: uppercase;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
        }

        .palette-section {
            margin-bottom: 50px;
        }

        .palette-title {
            font-size: 1rem;
            font-weight: 500;
            margin-bottom: 15px;
            color: #aaa;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .palette {
            display: flex;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }

        .swatch {
            flex: 1;
            aspect-ratio: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 10px 5px;
            cursor: pointer;
            transition: transform 0.2s, flex 0.2s;
            position: relative;
        }

        .swatch:hover {
            flex: 1.5;
        }

        .swatch-label {
            font-size: 0.7rem;
            font-weight: 600;
            text-align: center;
            opacity: 0.9;
        }

        .swatch-hex {
            font-size: 0.6rem;
            font-family: monospace;
            text-align: center;
            opacity: 0.7;
            margin-top: 2px;
        }

        .swatch.light-text {
            color: #fff;
        }

        .swatch.dark-text {
            color: #000;
        }

        .copied-toast {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: #667eea;
            color: #fff;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 500;
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .copied-toast.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }

        .base-indicator {
            position: absolute;
            top: 5px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0,0,0,0.3);
            color: #fff;
            font-size: 0.5rem;
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .export-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .export-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .export-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        #colorName {
            background: #0f0f23;
            border: 2px solid #333;
            color: #fff;
            padding: 12px 16px;
            font-size: 1rem;
            border-radius: 8px;
            width: 150px;
        }

        #colorName:focus {
            outline: none;
            border-color: #667eea;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 2000;
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal {
            background: #16213e;
            border-radius: 16px;
            padding: 30px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-title {
            font-size: 1.2rem;
            font-weight: 600;
        }

        .modal-close {
            background: none;
            border: none;
            color: #888;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 5px 10px;
        }

        .modal-close:hover {
            color: #fff;
        }

        .modal-content {
            flex: 1;
            overflow: auto;
        }

        .code-block {
            background: #0f0f23;
            border-radius: 8px;
            padding: 20px;
            font-family: 'Fira Code', monospace;
            font-size: 0.85rem;
            line-height: 1.6;
            overflow-x: auto;
            white-space: pre;
            color: #a5d6ff;
        }

        .modal-footer {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
        }

        .copy-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }

        .copy-btn:hover {
            opacity: 0.9;
        }

        @media (max-width: 600px) {
            .palette {
                flex-wrap: wrap;
            }
            
            .swatch {
                flex: 0 0 20%;
                aspect-ratio: 1;
            }
            
            .swatch:hover {
                flex: 0 0 20%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Color Palette Generator</h1>
        <p class="subtitle">Miešanie s bielou a čiernou podľa opacity</p>

        <div class="input-section">
            <div class="color-input-wrapper">
                <input type="color" id="colorPicker" value="#9D2065">
                <input type="text" id="hexInput" value="#9D2065" maxlength="7">
            </div>
            <input type="text" id="colorName" value="primary" placeholder="Názov farby">
        </div>

        <div class="export-buttons">
            <button class="export-btn" onclick="exportCSS()">Export CSS</button>
            <button class="export-btn" onclick="exportSCSS()">Export SCSS</button>
            <button class="export-btn" onclick="exportTailwind()">Export Tailwind</button>
            <button class="export-btn" onclick="exportBootstrap()">Export Bootstrap 5</button>
        </div>

        <div class="palette-section">
            <div class="palette-title">↑ Svetlejšie (mix s bielou)</div>
            <div class="palette" id="lightPalette"></div>
        </div>

        <div class="palette-section">
            <div class="palette-title">↓ Tmavšie (mix s čiernou)</div>
            <div class="palette" id="darkPalette"></div>
        </div>
    </div>

    <div class="copied-toast" id="toast">Skopírované!</div>

    <div class="modal-overlay" id="modalOverlay">
        <div class="modal">
            <div class="modal-header">
                <span class="modal-title" id="modalTitle">Export</span>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-content">
                <div class="code-block" id="codeBlock"></div>
            </div>
            <div class="modal-footer">
                <button class="copy-btn" onclick="copyCode()">Kopírovať kód</button>
            </div>
        </div>
    </div>

    <script>
        const colorPicker = document.getElementById('colorPicker');
        const hexInput = document.getElementById('hexInput');
        const lightPalette = document.getElementById('lightPalette');
        const darkPalette = document.getElementById('darkPalette');
        const toast = document.getElementById('toast');

        // Stupne pre paletu
        const lightSteps = [
            { name: '50', opacity: 0.05 },
            { name: '100', opacity: 0.10 },
            { name: '200', opacity: 0.20 },
            { name: '300', opacity: 0.30 },
            { name: '400', opacity: 0.40 },
            { name: '500', opacity: 0.50 },
            { name: '600', opacity: 0.60 },
            { name: '700', opacity: 0.70 },
            { name: '800', opacity: 0.80 },
            { name: '900', opacity: 0.90 },
            { name: '950', opacity: 0.95 },
            { name: '1000', opacity: 1.00, isBase: true },
        ];

        const darkSteps = [
            { name: '1000', opacity: 1.00, isBase: true },
            { name: '1050', opacity: 0.95 },
            { name: '1100', opacity: 0.90 },
            { name: '1200', opacity: 0.80 },
            { name: '1300', opacity: 0.70 },
            { name: '1400', opacity: 0.60 },
            { name: '1500', opacity: 0.50 },
            { name: '1600', opacity: 0.40 },
            { name: '1700', opacity: 0.30 },
            { name: '1800', opacity: 0.20 },
            { name: '1900', opacity: 0.10 },
        ];

        function hexToRgb(hex) {
            const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
            return result ? {
                r: parseInt(result[1], 16),
                g: parseInt(result[2], 16),
                b: parseInt(result[3], 16)
            } : null;
        }

        function rgbToHex(r, g, b) {
            return '#' + [r, g, b].map(x => {
                const hex = Math.round(x).toString(16);
                return hex.length === 1 ? '0' + hex : hex;
            }).join('').toUpperCase();
        }

        function mixWithWhite(r, g, b, opacity) {
            // farba * opacity + biela * (1 - opacity)
            return {
                r: r * opacity + 255 * (1 - opacity),
                g: g * opacity + 255 * (1 - opacity),
                b: b * opacity + 255 * (1 - opacity)
            };
        }

        function mixWithBlack(r, g, b, opacity) {
            // farba * opacity + čierna * (1 - opacity)
            // čierna je 0, takže: farba * opacity
            return {
                r: r * opacity,
                g: g * opacity,
                b: b * opacity
            };
        }

        function getLuminance(r, g, b) {
            // Relatívna luminancia pre určenie farby textu
            const [rs, gs, bs] = [r, g, b].map(c => {
                c = c / 255;
                return c <= 0.03928 ? c / 12.92 : Math.pow((c + 0.055) / 1.055, 2.4);
            });
            return 0.2126 * rs + 0.7152 * gs + 0.0722 * bs;
        }

        function createSwatch(hex, name, isBase = false) {
            const rgb = hexToRgb(hex);
            const luminance = getLuminance(rgb.r, rgb.g, rgb.b);
            const textClass = luminance > 0.4 ? 'dark-text' : 'light-text';

            const swatch = document.createElement('div');
            swatch.className = `swatch ${textClass}`;
            swatch.style.backgroundColor = hex;
            swatch.innerHTML = `
                ${isBase ? '<span class="base-indicator">Base</span>' : ''}
                <span class="swatch-label">${name}</span>
                <span class="swatch-hex">${hex}</span>
            `;

            swatch.addEventListener('click', () => {
                navigator.clipboard.writeText(hex);
                toast.textContent = `${hex} skopírované!`;
                toast.classList.add('show');
                setTimeout(() => toast.classList.remove('show'), 1500);
            });

            return swatch;
        }

        function generatePalette() {
            const hex = hexInput.value;
            const rgb = hexToRgb(hex);

            if (!rgb) return;

            // Vyčistiť palety
            lightPalette.innerHTML = '';
            darkPalette.innerHTML = '';

            // Svetlá paleta (mix s bielou)
            lightSteps.forEach(step => {
                const mixed = mixWithWhite(rgb.r, rgb.g, rgb.b, step.opacity);
                const mixedHex = rgbToHex(mixed.r, mixed.g, mixed.b);
                lightPalette.appendChild(createSwatch(mixedHex, step.name, step.isBase));
            });

            // Tmavá paleta (mix s čiernou)
            darkSteps.forEach(step => {
                const mixed = mixWithBlack(rgb.r, rgb.g, rgb.b, step.opacity);
                const mixedHex = rgbToHex(mixed.r, mixed.g, mixed.b);
                darkPalette.appendChild(createSwatch(mixedHex, step.name, step.isBase));
            });
        }

        // Event listeners
        colorPicker.addEventListener('input', (e) => {
            hexInput.value = e.target.value.toUpperCase();
            generatePalette();
        });

        hexInput.addEventListener('input', (e) => {
            let value = e.target.value;
            if (!value.startsWith('#')) {
                value = '#' + value;
            }
            if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                colorPicker.value = value;
                generatePalette();
            }
        });

        // Inicializácia
        generatePalette();

        // Export funkcie
        const modalOverlay = document.getElementById('modalOverlay');
        const modalTitle = document.getElementById('modalTitle');
        const codeBlock = document.getElementById('codeBlock');
        const colorName = document.getElementById('colorName');

        function getAllColors() {
            const hex = hexInput.value;
            const rgb = hexToRgb(hex);
            const name = colorName.value || 'primary';
            
            const colors = { light: {}, dark: {} };

            lightSteps.forEach(step => {
                const mixed = mixWithWhite(rgb.r, rgb.g, rgb.b, step.opacity);
                colors.light[step.name] = rgbToHex(mixed.r, mixed.g, mixed.b);
            });

            darkSteps.forEach(step => {
                const mixed = mixWithBlack(rgb.r, rgb.g, rgb.b, step.opacity);
                colors.dark[step.name] = rgbToHex(mixed.r, mixed.g, mixed.b);
            });

            return { colors, name };
        }

        function exportCSS() {
            const { colors, name } = getAllColors();
            
            let css = `:root {\n`;
            css += `    /* ${name} - Light (mix with white) */\n`;
            
            for (const [step, hex] of Object.entries(colors.light)) {
                css += `    --${name}-${step}: ${hex};\n`;
            }
            
            css += `\n    /* ${name} - Dark (mix with black) */\n`;
            
            for (const [step, hex] of Object.entries(colors.dark)) {
                if (step !== '1000') { // Skip duplicate 1000
                    css += `    --${name}-${step}: ${hex};\n`;
                }
            }
            
            css += `}`;

            modalTitle.textContent = 'CSS Variables';
            codeBlock.textContent = css;
            modalOverlay.classList.add('show');
        }

        function exportTailwind() {
            const { colors, name } = getAllColors();
            
            let config = `// tailwind.config.js\nmodule.exports = {\n`;
            config += `    theme: {\n`;
            config += `        extend: {\n`;
            config += `            colors: {\n`;
            config += `                '${name}': {\n`;
            
            // Light shades
            for (const [step, hex] of Object.entries(colors.light)) {
                config += `                    '${step}': '${hex}',\n`;
            }
            
            // Dark shades (skip 1000 duplicate)
            for (const [step, hex] of Object.entries(colors.dark)) {
                if (step !== '1000') {
                    config += `                    '${step}': '${hex}',\n`;
                }
            }
            
            config += `                },\n`;
            config += `            },\n`;
            config += `        },\n`;
            config += `    },\n`;
            config += `}`;

            modalTitle.textContent = 'Tailwind Config';
            codeBlock.textContent = config;
            modalOverlay.classList.add('show');
        }

        function exportSCSS() {
            const { colors, name } = getAllColors();
            
            let scss = `// ${name} - Color Palette\n\n`;
            scss += `// Light shades (mix with white)\n`;
            
            for (const [step, hex] of Object.entries(colors.light)) {
                scss += `$${name}-${step}: ${hex};\n`;
            }
            
            scss += `\n// Dark shades (mix with black)\n`;
            
            for (const [step, hex] of Object.entries(colors.dark)) {
                if (step !== '1000') {
                    scss += `$${name}-${step}: ${hex};\n`;
                }
            }

            scss += `\n// Color map\n`;
            scss += `$${name}-colors: (\n`;
            
            for (const [step, hex] of Object.entries(colors.light)) {
                scss += `    '${step}': $${name}-${step},\n`;
            }
            for (const [step, hex] of Object.entries(colors.dark)) {
                if (step !== '1000') {
                    scss += `    '${step}': $${name}-${step},\n`;
                }
            }
            
            scss += `);\n`;

            modalTitle.textContent = 'SCSS Variables';
            codeBlock.textContent = scss;
            modalOverlay.classList.add('show');
        }

        function exportBootstrap() {
            const { colors, name } = getAllColors();
            
            let bs = `// Bootstrap 5 - ${name} Color Palette\n`;
            bs += `// Import this BEFORE bootstrap in your main SCSS file\n\n`;
            
            bs += `// 1. Color variables\n`;
            for (const [step, hex] of Object.entries(colors.light)) {
                bs += `$${name}-${step}: ${hex};\n`;
            }
            for (const [step, hex] of Object.entries(colors.dark)) {
                if (step !== '1000') {
                    bs += `$${name}-${step}: ${hex};\n`;
                }
            }

            bs += `\n// 2. Set as primary (optional)\n`;
            bs += `$primary: $${name}-1000;\n\n`;

            bs += `// 3. Add to theme colors\n`;
            bs += `$${name}: $${name}-1000;\n\n`;

            bs += `$custom-colors: (\n`;
            bs += `    "${name}": $${name},\n`;
            bs += `);\n\n`;

            bs += `// 4. Create color shades map for utilities\n`;
            bs += `$${name}-shades: (\n`;
            for (const [step, hex] of Object.entries(colors.light)) {
                bs += `    "${step}": $${name}-${step},\n`;
            }
            for (const [step, hex] of Object.entries(colors.dark)) {
                if (step !== '1000') {
                    bs += `    "${step}": $${name}-${step},\n`;
                }
            }
            bs += `);\n\n`;

            bs += `// 5. Usage example:\n`;
            bs += `// @import "your-variables";\n`;
            bs += `// @import "bootstrap/scss/bootstrap";\n`;
            bs += `//\n`;
            bs += `// Then use: .bg-${name}, .text-${name}, .btn-${name}\n`;
            bs += `// Or directly: background-color: $${name}-500;\n`;

            modalTitle.textContent = 'Bootstrap 5 SCSS';
            codeBlock.textContent = bs;
            modalOverlay.classList.add('show');
        }

        function closeModal() {
            modalOverlay.classList.remove('show');
        }

        function copyCode() {
            navigator.clipboard.writeText(codeBlock.textContent);
            toast.textContent = 'Kód skopírovaný!';
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 1500);
        }

        modalOverlay.addEventListener('click', (e) => {
            if (e.target === modalOverlay) {
                closeModal();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>
</html>