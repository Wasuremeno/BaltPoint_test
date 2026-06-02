<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Make Me a Hanzi - Stroke Order Viewer by Dmitrii Vlaskin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        .writer {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin: 10px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
        }

        #character-target {
            width: 300px;
            height: 300px;
            margin: 0 auto;
        }

        input {
            font-size: 20px;
            padding: 8px;
            width: 100px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            padding: 8px 16px;
            margin: 5px;
            font-size: 16px;
            cursor: pointer;
            background: #e0e0e0;
            border: none;
            border-radius: 4px;
            transition: background 0.2s;
        }

        button:hover {
            background: #d0d0d0;
        }

        .char-list-container {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .search-box {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .char-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(60px, 80px));
            gap: 10px;
            max-height: 400px;
            overflow-y: auto;
            padding: 5px;
        }

        .char-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 10px 5px;
            background: #f8f9fa;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid #e0e0e0;
        }

        .char-card:hover {
            background: #e3f2fd;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-color: #2196F3;
        }

        .char-card .character {
            font-size: 28px;
            font-weight: bold;
        }

        .char-card .pinyin {
            font-size: 11px;
            color: #666;
            margin-top: 4px;
        }

        .loading {
            text-align: center;
            padding: 20px;
            color: #666;
        }

        .result-count {
            margin-top: 10px;
            font-size: 14px;
            color: #666;
            text-align: right;
        }

        .active-char {
            background: #2196F3 !important;
            color: white !important;
        }

        .active-char .pinyin {
            color: #e0e0e0 !important;
        }

        h1 {
            font-size: 2em;
            margin: 0.67em 0;
        }

        h3 {
            font-size: 1.17em;
            margin: 1em 0;
        }

        p {
            margin: 1em 0;
        }

        .char-details {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .char-details h4 {
            margin: 0 0 10px 0;
            color: #333;
        }

        .char-details p {
            margin: 8px 0;
            line-height: 1.4;
        }

        .char-details strong {
            color: #555;
        }

        .etymology-trigger {
            cursor: help;
            border-bottom: 1px dotted #999;
            color: #2196F3;
            display: inline-block;
        }

        .etymology-tooltip {
            position: absolute;
            bottom: 100%;
            left: 0;
            margin-bottom: 10px;
            background: #333;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 13px;
            max-width: 300px;
            z-index: 1000;
            white-space: normal;
            word-wrap: break-word;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .etymology-tooltip::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 20px;
            width: 0;
            height: 0;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-top: 6px solid #333;
        }

        .admin-link {
            display: inline-block;
            margin-bottom: 10px;
            color: #666;
            text-decoration: none;
            font-size: 14px;
        }

        .admin-link:hover {
            color: #2196F3;
        }

        .stats {
            background: white;
            border-radius: 8px;
            padding: 10px 15px;
            margin: 10px 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            font-size: 14px;
            color: #666;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/hanzi-writer@3.7/dist/hanzi-writer.min.js"></script>
</head>

<body>
    <div class="container">
        <a href="/characters" class="admin-link">📋 Admin Panel</a>

        <h1>Hanzi Stroke Order Viewer by Dmitrii Vlaskin</h1>
        <div class="stats" id="stats">
            <span id="charCount">Loading characters...</span>
            <span>✅ Uses data from database</span>
        </div>

        <div style="text-align: center; margin: 20px 0;">
            <input type="text" id="charInput" maxlength="1" placeholder="字" value="你">
            <button onclick="loadCharacter()">Show Stroke Order</button>
            <button onclick="animateCharacter()">▶ Animate</button>
            <button onclick="resetCharacter()">↺ Reset</button>
        </div>

        <div class="writer">
            <div id="character-target"></div>
        </div>

        <div id="charDetails" class="char-details" style="display: none;">
            <h4>📖 Character Information</h4>
            <div id="charDetailsContent"></div>
        </div>

        <div class="char-list-container">
            <h3>📚 Character Library</h3>
            <input type="text" id="searchInput" class="search-box" placeholder="🔍 Search by character or pinyin...">
            <div id="charGrid" class="char-grid">
                <div class="loading">Loading characters...</div>
            </div>
            <div id="resultCount" class="result-count"></div>
        </div>

        <div id="info" style="background: white; padding: 15px; border-radius: 8px; margin-top: 10px;">
            <p><strong>💡 Tip:</strong> Click any character from the list to load it instantly. Search by character or
                pinyin.</p>
            <p>📖 I couldn't find any good website to learn how to draw Chinese characters so I created this one!</p>
        </div>
    </div>

    <script>
        let writer = null;
        let allCharacters = [];
        let defaultLoaded = false;

        // Загрузка данных из Laravel API
        async function loadCharacters() {
            try {
                const response = await fetch('/api/characters');
                const data = await response.json();

                allCharacters = data;
                document.getElementById('stats').innerHTML = `
                    <span>📊 ${allCharacters.length.toLocaleString()} Chinese characters with stroke order animations</span>
                    <span>✅ Data loaded from database</span>
                `;
                displayCharacters(allCharacters);

                // Загружаем дефолтный иероглиф "你" ТОЛЬКО ПОСЛЕ загрузки данных
                loadDefaultCharacter();
            } catch (error) {
                console.error('Error loading characters:', error);
                document.getElementById('charGrid').innerHTML = '<div class="loading">Error loading characters. Please refresh.</div>';
                // Даже если ошибка, пробуем загрузить "你" без данных
                loadDefaultCharacterFallback();
            }
        }

        function displayCharacters(characters) {
            const grid = document.getElementById('charGrid');
            const resultCount = document.getElementById('resultCount');

            if (!characters || characters.length === 0) {
                grid.innerHTML = '<div class="loading">No characters found</div>';
                resultCount.textContent = '0 characters';
                return;
            }

            resultCount.textContent = `${characters.length} characters`;

            const fragment = document.createDocumentFragment();

            characters.forEach(charData => {
                const card = document.createElement('div');
                card.className = 'char-card';
                card.innerHTML = `
                    <div class="character">${escapeHtml(charData.char)}</div>
                    <div class="pinyin">${escapeHtml(charData.pinyin || ' ')}</div>
                `;
                card.onclick = () => {
                    document.getElementById('charInput').value = charData.char;
                    loadCharacterWithData(charData);

                    document.querySelectorAll('.char-card').forEach(c => c.classList.remove('active-char'));
                    card.classList.add('active-char');
                };
                fragment.appendChild(card);
            });

            grid.innerHTML = '';
            grid.appendChild(fragment);
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/[&<>]/g, function(m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            });
        }

        function updateCharacterDetails(charData) {
            const detailsDiv = document.getElementById('charDetails');
            const contentDiv = document.getElementById('charDetailsContent');

            let html = `<p><strong>Character:</strong> ${escapeHtml(charData.char)}</p>`;
            if (charData.pinyin) html += `<p><strong>Pinyin:</strong> ${escapeHtml(charData.pinyin)}</p>`;
            if (charData.def) html += `<p><strong>Definition:</strong> ${escapeHtml(charData.def)}</p>`;
            if (charData.radical) html += `<p><strong>Radical:</strong> ${escapeHtml(charData.radical)}</p>`;
            if (charData.decomposition && charData.decomposition !== '？') html += `<p><strong>Decomposition:</strong> ${escapeHtml(charData.decomposition)}</p>`;

            if (charData.etymology) {
                const escapedEtymology = escapeHtml(charData.etymology).replace(/'/g, "\\'");
                html += `
                    <div style="position: relative; display: inline-block; margin-top: 5px;">
                        <span class="etymology-trigger"
                              onmouseenter="showEtymologyTooltip(this, '${escapedEtymology}')"
                              onmouseleave="hideEtymologyTooltip(this)">
                            📜 Etymology
                        </span>
                    </div>
                `;
            }

            contentDiv.innerHTML = html;
            detailsDiv.style.display = 'block';
        }

        function showEtymologyTooltip(element, text) {
            let tooltip = element.querySelector('.etymology-tooltip');
            if (!tooltip) {
                tooltip = document.createElement('div');
                tooltip.className = 'etymology-tooltip';
                tooltip.textContent = text;
                element.style.position = 'relative';
                element.appendChild(tooltip);
            }
            tooltip.style.display = 'block';
        }

        function hideEtymologyTooltip(element) {
            const tooltip = element.querySelector('.etymology-tooltip');
            if (tooltip) tooltip.style.display = 'none';
        }

        function filterCharacters() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();

            if (!searchTerm) {
                displayCharacters(allCharacters);
                return;
            }

            const filtered = allCharacters.filter(charData => {
                return charData.char === searchTerm ||
                    charData.char.toLowerCase().includes(searchTerm) ||
                    (charData.pinyin && charData.pinyin.toLowerCase().includes(searchTerm));
            });

            displayCharacters(filtered);
        }

        let debounceTimer;
        function debouncedFilter() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(filterCharacters, 300);
        }

        // Загрузка иероглифа с данными
        function loadCharacterWithData(charData) {
            const char = charData.char;
            if (!char || char.length === 0) return;

            const targetDiv = document.getElementById('character-target');
            targetDiv.innerHTML = '';

            try {
                if (writer && writer.teardown) {
                    writer.teardown();
                }

                writer = HanziWriter.create('character-target', char, {
                    width: 300,
                    height: 300,
                    showOutline: true,
                    showCharacter: false,
                    strokeAnimationSpeed: 1.5,
                    delayBetweenStrokes: 500
                });

                writer.animateCharacter();
                updateCharacterDetails(charData);
            } catch (error) {
                console.error('Character not supported:', char);
                targetDiv.innerHTML = '<div style="text-align:center;color:red;padding:20px;">Character not supported in Hanzi Writer</div>';
                document.getElementById('charDetails').style.display = 'none';
            }
        }

        function loadCharacter() {
            const char = document.getElementById('charInput').value;
            if (!char || char.length === 0) return;

            const targetDiv = document.getElementById('character-target');
            targetDiv.innerHTML = '';

            try {
                if (writer && writer.teardown) {
                    writer.teardown();
                }

                writer = HanziWriter.create('character-target', char, {
                    width: 300,
                    height: 300,
                    showOutline: true,
                    showCharacter: false,
                    strokeAnimationSpeed: 1.5,
                    delayBetweenStrokes: 500
                });

                writer.animateCharacter();

                const charData = allCharacters.find(c => c.char === char);
                if (charData) {
                    updateCharacterDetails(charData);
                } else {
                    document.getElementById('charDetails').style.display = 'none';
                }
            } catch (error) {
                console.error('Character not supported:', char);
                targetDiv.innerHTML = '<div style="text-align:center;color:red;padding:20px;">Character not supported in Hanzi Writer</div>';
                document.getElementById('charDetails').style.display = 'none';
            }
        }

        function loadDefaultCharacter() {
            const defaultCharData = allCharacters.find(c => c.char === '你');

            if (defaultCharData && !defaultLoaded) {
                defaultLoaded = true;
                loadCharacterWithData(defaultCharData);

                setTimeout(() => {
                    const cards = document.querySelectorAll('.char-card');
                    cards.forEach(card => {
                        const charSpan = card.querySelector('.character');
                        if (charSpan && charSpan.textContent === '你') {
                            card.classList.add('active-char');
                        }
                    });
                }, 100);
            } else if (!defaultLoaded) {
                loadDefaultCharacterFallback();
            }
        }

        function loadDefaultCharacterFallback() {
            if (defaultLoaded) return;
            defaultLoaded = true;

            const targetDiv = document.getElementById('character-target');
            targetDiv.innerHTML = '';

            try {
                writer = HanziWriter.create('character-target', '你', {
                    width: 300,
                    height: 300,
                    showOutline: true,
                    showCharacter: false,
                    strokeAnimationSpeed: 1.5
                });
                writer.animateCharacter();
                document.getElementById('charDetails').style.display = 'none';
            } catch(e) {
                console.log('Default char "你" loading fallback');
                targetDiv.innerHTML = '<div style="text-align:center;color:#999;padding:20px;">Loading stroke order for "你"...</div>';
            }
        }

        function animateCharacter() {
            if (writer && writer.animateCharacter) writer.animateCharacter();
        }

        function resetCharacter() {
            if (writer && writer.loopCharacterAnimation) writer.loopCharacterAnimation();
        }

        // НЕМЕДЛЕННО загружаем "你" при старте (параллельно с загрузкой данных)
        (function init() {
            // Сразу показываем "你" без ожидания данных
            loadDefaultCharacterFallback();
            // Затем загружаем данные
            loadCharacters();
        })();

        document.getElementById('searchInput').addEventListener('input', debouncedFilter);
        document.getElementById('charInput').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') loadCharacter();
        });
    </script>
</body>

</html>
