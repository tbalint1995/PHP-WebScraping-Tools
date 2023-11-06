> [!NOTE]
> A projekt célja az OGYÉI gyógyszeradatbázisból származó gyógyszerek kiegészítő adatainak kinyerése, azok tárolása és elérhetővé tétele a 'source.txt' fájlban található azonosítók alapján. A program célja, hogy automatizálja ezt a folyamatot, és lehetővé tegye a gyógyszerek adatainak hatékony és pontos gyűjtését a weboldal tartalmának beolvasásával.


> [!IMPORTANT]
> Az elkészült eredménynek az alábbiaknak kell megfelelnie: :boom:

1. Az elkészült projekt tartalmaz egy futtatható SQL szkriptet, amely létrehozza az adatbázis táblaszerkezetét.

2. Emellett rendelkezik egy PHP állománnyal, amely felelős az adatok begyűjtéséért és azok adatbázisba való mentéséért.

3. Végül, a projektben található egy másik PHP állomány, amely lehetővé teszi az adatok keresését, megjelenítését és felhasználóbarát módon való elérését a felhasználók számára.

-------------------------------------------------------------------------------------------------------------

> [!NOTE]
> PHP Projekt Letöltése GitHubról :octocat:

1. Git telepítése: Telepítse a Git verziókezelőt, ha még nincs telepítve a gépén.

2. Telepíts egy kódszerkesztőt, például a Visual Studio Code-ot.

3. Telepíts egy lokális fejlesztői környezetet, például a Laragon-t.

4. Projekt klónozása: Nyissa meg a parancssor/terminált, majd navigáljon a projekt letöltéséhez kívánt mappába.

5. Állítsd be a terminált a projekt gyökérkönyvtárára.

6. Konfiguráld az adatbázis kapcsolódási adataidat a "config.example.php" fájlból a "config.php" fájlban történő átnevezés során.

7. Hozz létre egy adatbázist.

8. Futtasd a `php medicine migrate` parancsot a táblaszerkezet létrehozásához.

9. Futtasd a `php medicine store` parancsot az adatok lekéréséhez és tárolásához.

> [!NOTE]
> Indítás :running:

1. Állítsd be a terminált úgy, hogy a projekt public mappájára irányuljon.

2. Indítsd el a beépített PHP fejlesztői szerverét a `php -S localhost:8000` paranccsal.

3. Nyisd meg a "http://localhost:8000" címet a böngésződben a projekt weboldalának megtekintéséhez.