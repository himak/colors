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
    </script>
</body>
</html>