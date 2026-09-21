# DayQuest
Web-based local tourist day-visit planner for places within 25 km of Panadura, Sri Lanka.

**Stack:** HTML/CSS/JS + Bootstrap · PHP · MySQL · Leaflet + OpenStreetMap · Leaflet Routing Machine + OSRM · OpenWeatherMap (proxied via PHP)

## Setup
1. Install WAMP; start Apache and MySQL.
2. Clone this repo into `www/dayquest`.
3. In phpMyAdmin, import `sql/schema.sql`, then `sql/seed.sql`.
4. Copy `config/config.example.php` to `config/config.php` and fill in credentials.
5. Create an admin: `php tools/create_admin.php `.
6. Open `http://localhost/dayquest/public/`.

## Structure
`public/` tourist UI · `api/` JSON endpoints · `admin/` admin panel · `assets/` css/js/img · `sql/` schema + seed · `config/` settings · `tools/` CLI helpers
