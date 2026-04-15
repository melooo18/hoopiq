document.addEventListener('DOMContentLoaded', () => {
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('searchInput');
    const loader = document.getElementById('loader');
    const loaderText = document.getElementById('loaderText');
    const resultsArea = document.getElementById('resultsArea');
    
    // Player Stats Elements
    const playerNameEl = document.getElementById('playerName');
    const playerTeamEl = document.getElementById('playerTeam');
    const playerPositionEl = document.getElementById('playerPosition');
    const playerDraftEl = document.getElementById('playerDraft');
    const playerHeightEl = document.getElementById('playerHeight');
    const playerWeightEl = document.getElementById('playerWeight');

    // AI Analysis Elements
    const aiContentEl = document.getElementById('aiContent');

    searchForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const query = searchInput.value.trim();
        if (!query) return;

        // Reset UI
        resultsArea.classList.add('d-none');
        loader.classList.remove('d-none');
        loaderText.textContent = 'Fetching player data from BallDontLie...';
        aiContentEl.innerHTML = '<p class="text-muted text-center my-4 ai-waiting">Waiting to generate analysis...</p>';

        try {
            // Step 1: Search Player
            const searchResponse = await fetch(`api/search_player.php?q=${encodeURIComponent(query)}`);
            const searchData = await searchResponse.json();

            if (searchData.error) {
                let errMsg = `Error: ${searchData.error}`;
                if (searchData.code) errMsg += `\nCode: ${searchData.code}`;
                if (searchData.curl_error) errMsg += `\nCurl: ${searchData.curl_error}`;
                if (searchData.response && searchData.response.message) errMsg += `\nMessage: ${searchData.response.message}`;
                alert(errMsg);
                loader.classList.add('d-none');
                return;
            }

            if (!searchData.data || searchData.data.length === 0) {
                alert('No player found. Try a different name.');
                loader.classList.add('d-none');
                return;
            }

            const player = searchData.data[0];

            // Populate Player Card
            playerNameEl.textContent = `${player.first_name} ${player.last_name}`;
            playerTeamEl.textContent = player.team ? player.team.full_name : 'Unknown Team';
            playerPositionEl.textContent = player.position || 'N/A';
            playerDraftEl.textContent = player.draft_year || 'N/A';
            playerHeightEl.textContent = player.height ? player.height : 'N/A';
            playerWeightEl.textContent = player.weight ? `${player.weight} lbs` : 'N/A';

            // Show results area but keep AI loading
            loader.classList.add('d-none');
            resultsArea.classList.remove('d-none');

            // Set AI block to loading state
            aiContentEl.innerHTML = `
                <div class="d-flex flex-column align-items-center justify-content-center h-100 my-4 text-warning">
                    <div class="spinner-grow mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mb-0 fw-semibold ai-waiting">Gemini is analyzing ${player.first_name} ${player.last_name}...</p>
                </div>
            `;

            // Step 2: Request AI Analysis
            const aiResponse = await fetch('api/analyze_player.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ player: player })
            });

            const aiData = await aiResponse.json();

            if (aiData.error) {
                let errorHtml = `<p class="text-danger my-4"><i class="bi bi-exclamation-triangle-fill"></i> AI Error: ${aiData.error}</p>`;
                if (aiData.code) errorHtml += `<p class="text-warning small">Code: ${aiData.code}</p>`;
                if (aiData.curl_error) errorHtml += `<p class="text-warning small">Curl Error: ${aiData.curl_error}</p>`;
                if (aiData.raw) errorHtml += `<p class="text-muted small" style="word-break: break-all;">Raw: ${aiData.raw}</p>`;
                aiContentEl.innerHTML = errorHtml;
            } else if (aiData.analysis) {
                // Convert simple markdown/newlines to HTML
                let formattedText = aiData.analysis
                    .replace(/\n\n/g, '</p><p>')
                    .replace(/\n/g, '<br>')
                    .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                
                aiContentEl.innerHTML = `<p class="mb-0 lh-lg">${formattedText}</p>`;
            }

        } catch (error) {
            console.error(error);
            alert('An unexpected error occurred.');
            loader.classList.add('d-none');
        }
    });
});
