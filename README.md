## About Project

This is a PHP/Laravel Telegram Bot which allows the students of University Of Tehran to review their professors work and behaviour and know the ones they don't know better.

## Setting it up

Add your bot private key in .env file with the title "BOT_KEY".
Connect Telegram webhook to the "/" route of your app(For secuurity reasons I recommend you to change it to a route not known by anyone.)
You're ready to go.

### Profiles and information
The information of professors used in the app is avalaible in "profiles.json" file in public directory.
use "makeColleges" and then "makeProfessors" routes to fetch the data from json file and seed it in your database.
