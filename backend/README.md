# Mijn Rooster (Server/API)

Mijn Rooster Server is een eenvoudige PHP-backend die fungeert als een koppeling tussen de Mijn Rooster-applicatie en Zermelo. Deze server moet zelf worden gehost en biedt meerdere endpoints voor het ophalen van gebruikers- en roosterinformatie.

## 📥 Installatie & Hosting

De backend kan eenvoudig worden gehost op een server met **NGINX** of **Apache**. Volg de onderstaande stappen om de API correct te installeren en te configureren.

### 🔹 Vereisten
- **PHP 8.0+** (met cURL-extensie ingeschakeld)
- **Webserver:** Apache met mod_rewrite of NGINX
- **Toegang tot de Zermelo API**

### 🔹 Installatie-instructies
1. **Download de code**
   ```bash
   git clone https://github.com/Mijn-Rooster/mijnrooster.git
   cd mijnrooster
   ```
2. **Plaats de bestanden op je webserver**
   - Kopieer backend/api naar je servermap
   - Voor **Apache**, zorg ervoor dat `.htaccess` correct is ingesteld.
   - Voor **NGINX**, voeg een proxy-configuratie toe.
4. **Configuratie instellen**
   - Ga naar de `Config/` map.
   - Kopieer het voorbeeldconfiguratiebestand:
     ```bash
     cp Config/config.php.default Config/config.php
     ```
   - Open `Config/config.php` en vul de juiste waarden in:
     ```php
     define('TENANT_NAME', 'Mijn School');
     define('CONNECT_CODE', 'jouw-koppel-wachtwoord');
     define('DEBUG_MODE', false);
     define('DOCENT_SCHEDULE', true);
     define('ZERMELO_PORTAL_URL', 'https://school.zportal.nl');
     define('ZERMELO_API_TOKEN', 'jouw-api-token');
     ```

5. **Zermelo API Token instellen**
   - Ga naar [Zermelo API instellingen](https://support.zermelo.nl/guides/applicatiebeheerder/koppelingen/overige-koppelingen-2/koppeling-met-overige-externe-partijen).
   - Maak een API-koppeling aan voor externe partijen.
   - Voeg de gegenereerde API-token toe in `Config/config.php` onder `ZERMELO_API_TOKEN`.

6. **Controleer of de API werkt**
   - Open een browser en ga naar:
     ```
     http://jouwserver.nl/
     ```
   - Je zou een JSON-respons moeten krijgen met de tekst "Mijn Rooster API" oid.

---

## 🚀 API Endpoints

### 🔹 Gebruiker ophalen
- **Endpoint:** `GET /v1/schools/{schoolInSchoolYear}/user/{userId}`
- **Beschrijving:** Haalt informatie op over een specifieke gebruiker.
- **Query parameters:**
  - `schoolInSchoolYear` (verplicht) - De ID van de school
  - `userId` (verplicht) - De gebruikers-ID (bijv. `GIJS` of `545959`)
  - `type` (optioneel) - Type gebruiker: `student` of `teacher`

### 🔹 Rooster ophalen
- **Endpoint:** `GET /v1/schedule/{userId}`
- **Beschrijving:** Haalt het rooster op van een specifieke gebruiker.
- **Query parameters:**
  - `userId` (verplicht) - De gebruikers-ID
  - `start` (optioneel) - Startdatum in UTC Unix tijd
  - `end` (optioneel) - Einddatum in UTC Unix tijd

### 🔹 Lijst van scholen ophalen
- **Endpoint:** `GET /v1/schools`
- **Beschrijving:** Haalt een lijst op van alle scholen.

---

## 🛠️ Development

### 🔹 Backend

De backend verzorgt de communicatie tussen de Zermelo API en de Mijn Rooster apparaten.

#### Start met ontwikkelen

Open de backend-map:
```bash
cd backend
```

#### Installatie

Dit is niet nodig wanneer je in Codespaces werkt.
```bash
npm install
```

Je moet ook het configuratiebestand kopiëren en invullen:
```bash
cp Config/config.default.php Config/config.php
```
Voor ontwikkeldoeleinden kun je de configuratiedetails opvragen bij **@DVDdisk7**.

#### Start de backend-server

```bash
npm start
```
De backend draait nu op:  
👉 **https://localhost:8000**

### 🔹 Zermelo Developer Portal

Onze applicatie communiceert met de Zermelo API om roosters op te halen. Voor test- en ontwikkeldoeleinden is er een testomgeving beschikbaar:

- **Portal URL:** [partner-7206.zportal.nl](https://partner-7206.zportal.nl)
- **Gebruikersnaam:** Zie je e-mail
- **Wachtwoord:** Zie je e-mail

Meer informatie over de API:
- **API Koppeling instellen:** [Zermelo API](https://support.zermelo.nl/guides/applicatiebeheerder/koppelingen/overige-koppelingen-2/koppeling-met-overige-externe-partijen)
- **Documentatie over roosters:** [Zermelo Liveschedule](https://support.zermelo.nl/guides/developers-api/liveschedule#background)
- **Documentatie over gebruikers:** [Zermelo Users API](https://support.zermelo.nl/guides/developers-api/users)

---

## 📩 Ondersteuning
Heb je vragen, suggesties of bugs gevonden? Open een issue op de [GitHub Issues-pagina](https://github.com/Mijn-Rooster/mijnrooster-api/issues) of draag bij via een pull request! 🎉

    
