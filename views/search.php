<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gyógyszerkereső - KARDI-SOFT</title>

    <!-- CSS stílusok -->
    <style>
        body,
        html {
            height: 100%;
            padding: 0;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        .container {
            width: 100%;
            border: 1px solid grey;
            box-shadow: 0 0 7px grey;
            padding: 1rem;
            margin: auto;
        }

        input[type="search"] {
            border: 1px solid grey;
            box-shadow: 0 0 3px lightblue;
            border-radius: 7px;
            padding: 7px;
            font-size: 1.25rem;
            width: 100%;
            display: block;
        }

        h3+ul {
            padding: 0;
            list-style-type: none;
        }

        h3+ul>li {
            display: flex;
            flex-direction: column;
        }

        h3+ul>li>div {
            padding: 7px;
            width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        h3+ul>li:nth-child(odd) {
            background-color: #e2f8ff;
        }

        [data-item] {
            cursor: pointer;
        }

        [data-item]:hover {
            outline: 1px outset #d2ffcf;
            background-color: #d2ffcf !important;
        }

        #modal {
            position: fixed;
            background-color: rgba(0, 0, 0, 0.8);
            width: 100%;
            height: 100%;
            display: none;
            justify-content: center;
            align-items: center;
            top: 0;
            left: 0;
        }

        #modal>* {
            width: 100%;
            max-width: 500px;
            background: #fff;
            border: grey;
            min-height: 100px;
        }

        @media screen and (min-width: 576px) {
            .container {
                width: 500px;
                margin-top: 1rem;
            }
        }


        @media screen and (min-width: 768px) {
            .container {
                width: 750px;
            }

            h3+ul>li>div {
                width: 33%;
            }

            h3+ul>li {
                flex-direction: row;
            }
        }


        @media screen and (min-width: 998px) {
            .container {
                width: 960px;
            }
        }

        .inner {
            padding: 20px;
            list-style-type: none;
        }

        .inner>li {
            padding: 10px;
            border-bottom: 1px solid lightgrey;
        }
    </style>
</head>

<body>
    <div class="container">
        <h3>Gyógyszerek keresése:</h3>
        <!-- Kereső űrlap -->
        <form action="">
            <label for="search">Keresés</label>
            <input type="search" value="" placeholder="Név, hatóanyag vagy atc kód" id="search">
        </form>

        <h3>A keresés eredménye:</h3>
        <ul></ul>
    </div>
    <!-- Részletes információk modális ablakja -->
    <div id="modal">
        <ul class="inner"></ul>
    </div>
    <script>
        // Keresési eredményeket tartalmazó konténer elem kiválasztása
        const resultsContainer = document.querySelector('h3 + ul');

        // Keresési eredmények lekérése és megjelenítése
        function getList(kw = '') {
            fetch('/search', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        kw: kw
                    })
                })
                .then(response => {
                    if (!response.ok)
                        throw response;
                    else
                        return response.json()
                })
                .then(results => {
                    // Keresési eredmények megjelenítése
                    //A keresési eredmény helyességének könnyebb azonosítása miatt mindhárom oszlop elhelyezésre került
                    resultsContainer.innerHTML = results.map(item => `<li data-item='${JSON.stringify(item)}'>
                    <div>${item.name}</div>
                    <div>${item.ingredient}</div>
                    <div>${item.atc}</div>
                </li>`)
                        .join('')

                })
                .catch(async err => {
                    alert('Hiba történt! ' + JSON.stringify(err))
                    /*
                    opcionális továbbfejlesztés
                    const errors = await err.json();
                    visszaadott hiba objektum tartalmának megjelenítése az errors -ból                
                    */
                })
                .finally(() => {
                    rowClickEvent()
                })
        }

        // Oldal betöltésekor keresési eredmények lekérése (üres keresés)
        window.onload = getList('');

        let timer = false;
        // Kereső mező változásának figyelése és késleltetett keresés indítása
        document.querySelector('input[type="search"]')
            .oninput = e => {
                resultsContainer.innerHTML = 'Loading ...'
                if (timer) {
                    clearTimeout(timer)
                    timer = false;
                }
                const el = e.target;
                timer = setTimeout(() => getList(el.value), 1000)
            }

        // Részletes információk modális ablak megjelenítése
        function showModal(item, callback) {
            const inner = document.querySelector('.inner');
            inner.innerHTML = ''
            for (let key in item) {

                (item[key]) ? inner.innerHTML += `<li>
                    ${key.toUpperCase().replace(/_/g, ' ')}: <b>${item[key]}</b>
                </li>`: ''
            }
            callback()
        }

        // Sorokra kattintás eseménykezelő regisztrálása
        const rowClickEvent = function() {
            document.querySelectorAll('[data-item]').forEach(el => {
                el.onclick = () => {
                    const item = JSON.parse(el.dataset.item)
                    showModal(item, () => {
                        const mymodal = document.getElementById('modal');
                        mymodal.style.display = 'flex';
                        mymodal.onclick = (e) => e.target.style.display = 'none'
                        document.querySelector('.inner').onclick = e => {
                            e.stopPropagation()
                        }
                    });
                }
            })
        }
    </script>
</body>

</html>