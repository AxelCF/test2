<html>

<head>
    <meta charset="UTF-8" />
    <title>Test application</title>
</head>

<body>
    <iframe src="https://player.twitch.tv/?channel=akiseruo&parent=localhost" height="720" width="1080" allowfullscreen>
    </iframe>

    <iframe src="https://www.twitch.tv/embed/akiseruo/chat?parent=localhost" height="700" width="350">
    </iframe>
    <script>
        // ID de l'application récupéré après l'avoir enregistrée
        const CLIENT_ID = "3gen03ele5257tc7gk5xviofrflq25";

        // Adresse où l'on veut que l'utilisateur soit redirigé après avoir autorisé
        // l'application. Cette adresse DOIT être l'une de celles déclarées dans
        // l'application sur dev.twitch.tv !!
        const REDIRECT_URI = "http://localhost:8000/main";

        // Liste des éléments auxquels on souhaite accéder...  On reparlera de ça un
        // peu plus tard ;)
        const SCOPES = [];

        // Diverses fonctions utilitaires
        const helpers = {
            // Encode un objet sous forme d'une querystring utilisable dans une URL :
            // {"name": "Truc Muche", "foo": "bar"}  ->  "name=Truc+Muche&foo=bar"
            encodeQueryString: function(params) {
                const queryString = new URLSearchParams();
                for (let paramName in params) {
                    queryString.append(paramName, params[paramName]);
                }
                return queryString.toString();
            },

            // Décode une querystring sous la forme d'un objet :
            // "name=Truc+Muche&foo=bar"  ->  {"name": "Truc Muche", "foo": "bar"}
            decodeQueryString: function(string) {
                const params = {};
                const queryString = new URLSearchParams(string);
                for (let [paramName, value] of queryString) {
                    params[paramName] = value;
                }
                return params;
            },

            // Récupère et décode les paramètres de l'URL
            getUrlParams: function() {
                return helpers.decodeQueryString(location.hash.slice(1));
            },
        };

        // Fonctions liées à Twitch
        const twitch = {
            // Vérifie si l'utilisateur est authentifié ou non
            isAuthenticated: function() {
                const params = helpers.getUrlParams();
                return params["access_token"] !== undefined;
            },

            // Redirige l'utilisateur sur la page d'authentification de Twitch avec les
            // bons paramètres
            authentication: function() {
                const params = {
                    client_id: CLIENT_ID,
                    redirect_uri: REDIRECT_URI,
                    response_type: "token",
                    scope: SCOPES.join(" "),
                };
                const queryString = helpers.encodeQueryString(params);
                const authenticationUrl = `https://id.twitch.tv/oauth2/authorize?${queryString}`;
                location.href = authenticationUrl;
            },
        };

        // Fonction principale
        function main() {
            // On lance l'authentification si l'utilisateur n'est pas authentifié
            if (!twitch.isAuthenticated()) {
                twitch.authentication();
            } else {
                alert("L'utilisateur a bien autorisé l'application !");
            }
        }

        // On appelle la fonction main() lorsque la page a fini de charger
        window.onload = main;
    </script>
</body>

</html>