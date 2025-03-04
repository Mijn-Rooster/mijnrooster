# Mijn Rooster (Applicatie)

Mijn Rooster is een applicatie waarmee gebruikers eenvoudig hun lesrooster kunnen bekijken. De applicatie ondersteunt meerdere besturingssystemen en biedt handige functies zoals weekoverzicht, automatische uitlogopties en ondersteuning voor barcodescanners.

## 📥 Installatie

1. **Download de laatste versie** via [GitHub Releases](https://github.com/Mijn-Rooster/mijnrooster/releases).  
   Beschikbaar voor:
   - **Linux** (ARM en 64-bit)
   - **Windows** (64-bit)
   - **MacOS** (niet getest)

2. **Eerste configuratie**

   - Voer de **API-url** en het **koppelwachtwoord** in die zijn ingesteld voor de Mijn Rooster API.
   - Selecteer de **locatie** waarvoor het rooster moet worden weergegeven.
   - Stel een **beheerderswachtwoord** in om toegang te krijgen tot het instellingenmenu.

4. **Voltooi de installatie**
   - Na een succesvolle koppeling komt u op het **hoofdscherm** terecht.

---

## 📌 Gebruik

### 🔹 Startscherm

![image](https://github.com/user-attachments/assets/326241a8-1804-4908-8bf2-cabeb354d514)

Op het hoofdscherm kan de gebruiker de datum en tijd bekijken en inloggen met behulp van zijn/haar leerlingnummer of met behulp van de barcodescanner. Het is aan te raden om gebruik te maken van een touchscreen of eventuele numpad-bediening.

### Offline-modus

![image](https://github.com/user-attachments/assets/40d33c2c-879b-497f-b571-1c462599c03f)

Wanneer de applicatie niet meer verbonden is met het internet of de server gaat deze in “buiten gebruik” modus. In deze modus is Mijn Rooster niet te gebruiken en is alleen het instellingenmenu nog bereikbaar voor eventuele aanpassingen of een reset.

### 🔹 Roosterweergave
- **Dagrooster:** Direct zichtbaar na inloggen.
  ![image](https://github.com/user-attachments/assets/d6d22ae4-f7dd-4d46-bf1d-f3d1ca1deabb)
- **Weekoverzicht:** Indien ingeschakeld in de instellingen.
  ![image](https://github.com/user-attachments/assets/5c720e1e-47fa-49a0-acbd-3dd680a2f7bc)
- **Uitval:** Wordt aangegeven met een **rode tegel** en de omschrijving *"les vervalt"*.
  ![image](https://github.com/user-attachments/assets/a449f819-206e-44c0-8820-9aba89280131)
- **Roosterwijzigingen:** Weergegeven met een **uitroepteken** en een beschrijving onder de les.
  ![image](https://github.com/user-attachments/assets/11d747a7-3c91-4dbf-8bc1-62b7ef4a10a0)
- **Bijzondere afspraken:** Gemarkeerd met een **gele tegel**.
  ![image](https://github.com/user-attachments/assets/234d2de1-d0b6-4318-b0ec-e616ec4bf12c)

---

## ⚙️ Instellingen & Opties

Het instellingenmenu is te openen via:
- **Menubalk:** `Alt` > `Mijn Rooster` > `Instellingen`
- **Sneltoets:** `Ctrl + ,`

Na invoeren van het **beheerderswachtwoord** zijn de volgende instellingen beschikbaar:

### 🔹 Algemeen

![image](https://github.com/user-attachments/assets/c64e93d2-e0d4-4625-a385-1f84535133b3)

- **School wijzigen:** Selecteer een andere school of locatie (indien gekoppeld aan Zermelo API).
- **Wachtwoord wijzigen:** Wijzig het beheerderswachtwoord.
- **Automatisch opstarten:** Start Mijn Rooster automatisch op na het opstarten van het besturingssysteem.
  - **MacOS:** Niet getest.
  - **Linux:** Mogelijk extra configuratie nodig: 
    1. Ga naar `~/.config/autostart/`
    2. Markeer Mijn Rooster als **trusted file**
    3. Pas de uitvoerregel aan: `mijnrooster-client`

### 🔹 Roosterweergave

![image](https://github.com/user-attachments/assets/8b1c0dc0-78a7-4afe-8a3e-afe281658ba7)

- **Weekoverzicht:** Voeg een extra tabblad toe voor het volledige weekrooster.
- **Automatisch uitloggen:** Stel in na hoeveel seconden inactiviteit een gebruiker wordt uitgelogd.

### 🔹 Bediening

![image](https://github.com/user-attachments/assets/c1585275-927e-4dd5-b52e-1d7a1f9651a5)

- **Numpad-bediening:** Voorkomt ongewenste interacties via een volledig toetsenbord.
  ![image](https://github.com/user-attachments/assets/1cbf711f-edd6-4838-ae27-6e0f0e380199)
- **Barcode scanner:** Werkt met scanners die barcodes invoeren als toetsenbordinput.

### 🔹 App Informatie

![image](https://github.com/user-attachments/assets/4f85c69a-6a46-4187-8ec7-18a0c66e9e5e)

- **Server-URL en versie** bekijken.
- **Applicatie resetten** bij wijzigingen in de serveromgeving.

---

## 🛠️ Development

De frontend is de software die draait op de Mijn Rooster-kioskapparaten.  
Het is gebaseerd op **Svelte** en **Electron**.

### Start met ontwikkelen

Open de frontend-map:
```bash
cd frontend
```

### Installatie

Dit is niet nodig wanneer je in Codespaces werkt.
```bash
npm install
```

### Starten

```bash
npm run dev
```
De applicatie wordt nu geopend in **fullscreen**. 
Wanneer je Codespaces gebruikt, moet je verbinding maken met de **noVNC viewer**. 
Ga naar _Ports_ > _desktop (6080)_ en open het _Forwarded Address_ in je browser.

### Builden

Bouw de applicatie voor verschillende besturingssystemen:
```bash
# Voor Windows
npm run build:win

# Voor macOS
npm run build:mac

# Voor Linux
npm run build:linux
```

De applicatie wordt automatisch gebouwd en gereleased op Github bij een pull request met nieuwe versie informatie.

---

## 🚀 Ondersteuning & Bijdragen
Heb je vragen, suggesties of bugs gevonden? Open een issue op de [GitHub Issues-pagina](https://github.com/Mijn-Rooster/mijnrooster/issues) of draag bij via een pull request! 🎉
