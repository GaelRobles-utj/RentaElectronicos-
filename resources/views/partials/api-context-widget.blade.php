<section id="api-context-widget" class="mb-6">

    <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                Información en tiempo real
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Ubicación, clima y tipo de cambio
            </p>
        </div>

        <span id="api-main-status"
              class="rounded-full bg-gray-100 px-3 py-1 text-xs
                     text-gray-700 dark:bg-gray-700 dark:text-gray-200">
            Conectando con los servicios...
        </span>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">

        <!-- UBICACIÓN -->
        <article class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm
                        dark:border-gray-700 dark:bg-gray-800">

            <h3 class="mb-3 font-semibold text-gray-900 dark:text-white">
                📍 Mi ubicación
            </h3>

            <p id="api-location-city"
               class="text-lg font-semibold text-gray-800 dark:text-white">
                Buscando ciudad...
            </p>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                Estado: <span id="api-location-state">—</span>
            </p>

            <p class="text-sm text-gray-600 dark:text-gray-300">
                País: <span id="api-location-country">—</span>
            </p>

            <p id="api-location-message"
               class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                Esperando el permiso de ubicación del navegador.
            </p>

            <button id="api-location-retry"
                    type="button"
                    class="mt-3 hidden rounded-lg bg-blue-700 px-3 py-2
                           text-sm font-medium text-white hover:bg-blue-800">
                Reintentar ubicación
            </button>
        </article>

        <!-- CLIMA -->
        <article class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm
                        dark:border-gray-700 dark:bg-gray-800">

            <h3 class="mb-3 font-semibold text-gray-900 dark:text-white">
                🌤️ Clima actual
            </h3>

            <p id="api-weather-temp"
               class="text-3xl font-bold text-gray-900 dark:text-white">
                — °C
            </p>

            <p id="api-weather-description"
               class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                Consultando condiciones...
            </p>

            <div class="mt-4 space-y-2 text-sm text-gray-600 dark:text-gray-300">
                <p>
                    💧 Humedad:
                    <strong id="api-weather-humidity">—</strong>
                </p>

                <p>
                    🌧️ Probabilidad de lluvia:
                    <strong id="api-weather-rain">—</strong>
                </p>
            </div>

            <p id="api-weather-updated"
               class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                Esperando datos...
            </p>
        </article>

        <!-- TIPO DE CAMBIO -->
        <article class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm
                        dark:border-gray-700 dark:bg-gray-800">

            <h3 class="mb-3 font-semibold text-gray-900 dark:text-white">
                💱 Tipo de cambio
            </h3>

            <p class="text-sm text-gray-600 dark:text-gray-300">
                Dólar a peso mexicano
            </p>

            <p id="api-fx-usd-mxn"
               class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                Consultando...
            </p>

            <p class="mt-4 text-sm text-gray-600 dark:text-gray-300">
                Peso mexicano a dólar
            </p>

            <p id="api-fx-mxn-usd"
               class="mt-1 text-lg font-semibold text-gray-800 dark:text-white">
                —
            </p>

            <p id="api-fx-updated"
               class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                Esperando datos...
            </p>
        </article>

    </div>
</section>

<script>
(() => {
    const getElement = (id) => document.getElementById(id);

    const CACHE_MINUTES = 10;

    function readCache(key, maxAge) {
        try {
            const value = sessionStorage.getItem(key);

            if (!value) {
                return null;
            }

            const cached = JSON.parse(value);

            if (Date.now() - cached.time > maxAge) {
                sessionStorage.removeItem(key);
                return null;
            }

            return cached.data;
        } catch (error) {
            return null;
        }
    }

    function saveCache(key, data) {
        try {
            sessionStorage.setItem(key, JSON.stringify({
                time: Date.now(),
                data: data
            }));
        } catch (error) {
            // La aplicación sigue funcionando aunque no haya caché.
        }
    }

    async function fetchJson(url, cacheKey, maxAge) {
        const cached = readCache(cacheKey, maxAge);

        if (cached !== null) {
            return cached;
        }

        const response = await fetch(url, {
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('No se pudo consultar el servicio.');
        }

        const data = await response.json();

        saveCache(cacheKey, data);

        return data;
    }

    function showDate(value) {
        const date = new Date(value);

        if (Number.isNaN(date.getTime())) {
            return 'Fecha no disponible';
        }

        return date.toLocaleString('es-MX');
    }

    // Consulta la ciudad, el estado y el país.
    async function loadLocation(latitude, longitude) {
        const url = new URL(
            'https://api.bigdatacloud.net/data/reverse-geocode-client'
        );

        url.searchParams.set('latitude', latitude);
        url.searchParams.set('longitude', longitude);
        url.searchParams.set('localityLanguage', 'es');

        const key = `re-place-${latitude.toFixed(3)}-${longitude.toFixed(3)}`;

        const data = await fetchJson(url.toString(), key, 86400000);

        getElement('api-location-city').textContent =
            data.city || data.locality || 'No disponible';

        getElement('api-location-state').textContent =
            data.principalSubdivision || 'No disponible';

        getElement('api-location-country').textContent =
            data.countryName || 'No disponible';

        getElement('api-location-message').textContent =
            'Ubicación consultada mediante una API de geocodificación.';
    }

    // Traducción básica de los códigos meteorológicos WMO.
    function getWeatherDescription(code) {
        const descriptions = {
            0: 'Despejado',
            1: 'Mayormente despejado',
            2: 'Parcialmente nublado',
            3: 'Nublado',
            45: 'Niebla',
            48: 'Niebla con escarcha',
            51: 'Llovizna ligera',
            53: 'Llovizna moderada',
            55: 'Llovizna intensa',
            61: 'Lluvia ligera',
            63: 'Lluvia moderada',
            65: 'Lluvia intensa',
            71: 'Nevada ligera',
            73: 'Nevada moderada',
            75: 'Nevada intensa',
            80: 'Chubascos ligeros',
            81: 'Chubascos moderados',
            82: 'Chubascos intensos',
            95: 'Tormenta eléctrica'
        };

        return descriptions[code] || 'Condiciones meteorológicas actuales';
    }

    // Consulta la temperatura, humedad y probabilidad de lluvia.
    async function loadWeather(latitude, longitude) {
        const url = new URL('https://api.open-meteo.com/v1/forecast');

        url.searchParams.set('latitude', latitude);
        url.searchParams.set('longitude', longitude);
        url.searchParams.set(
            'current',
            'temperature_2m,relative_humidity_2m,precipitation,weather_code'
        );
        url.searchParams.set('hourly', 'precipitation_probability');
        url.searchParams.set('forecast_days', '1');
        url.searchParams.set('timezone', 'auto');

        const key = `re-weather-${latitude.toFixed(2)}-${longitude.toFixed(2)}`;

        const data = await fetchJson(
            url.toString(),
            key,
            CACHE_MINUTES * 60000
        );

        if (!data.current) {
            throw new Error('La API no devolvió datos meteorológicos actuales.');
        }

        const current = data.current;

        getElement('api-weather-temp').textContent =
            `${current.temperature_2m} °C`;

        getElement('api-weather-humidity').textContent =
            `${current.relative_humidity_2m}%`;

        getElement('api-weather-description').textContent =
            getWeatherDescription(current.weather_code);

        // Busca la probabilidad de lluvia de la hora actual.
        const times = data.hourly?.time || [];
        const probabilities = data.hourly?.precipitation_probability || [];

        let index = times.indexOf(current.time);

        if (index < 0 && times.length > 0) {
            const targetTime = Date.parse(current.time);

            index = times.reduce((best, time, i) => {
                const distance = Math.abs(Date.parse(time) - targetTime);

                return distance <
                    Math.abs(Date.parse(times[best]) - targetTime)
                    ? i
                    : best;
            }, 0);
        }

        const rainProbability = probabilities[index];

        getElement('api-weather-rain').textContent =
            rainProbability !== undefined && rainProbability !== null
                ? `${rainProbability}%`
                : 'No disponible';

        getElement('api-weather-updated').textContent =
            `Hora local del pronóstico: ${current.time.replace('T', ' ')}`;
    }

    // Consulta el tipo de cambio USD/MXN.
    async function loadExchangeRate() {
        const status = getElement('api-fx-updated');

        try {
            const data = await fetchJson(
                'https://open.er-api.com/v6/latest/USD',
                're-fx-usd-mxn',
                3600000
            );

            if (data.result !== 'success' ||
                typeof data.rates?.MXN !== 'number') {
                throw new Error('Respuesta de cambio no válida.');
            }

            const rate = data.rates.MXN;

            getElement('api-fx-usd-mxn').textContent =
                `1 USD = ${rate.toLocaleString('es-MX', {
                    maximumFractionDigits: 4
                })} MXN`;

            getElement('api-fx-mxn-usd').textContent =
                `1 MXN ≈ ${(1 / rate).toFixed(4)} USD`;

            status.textContent =
                `Última actualización de la API: ${
                    showDate(data.time_last_update_utc)
                }`;
        } catch (error) {
            getElement('api-fx-usd-mxn').textContent =
                'Tipo de cambio no disponible';

            getElement('api-fx-mxn-usd').textContent = '—';

            status.textContent =
                'No se pudo consultar el servicio de cambio.';
        }
    }

    // Solicita la ubicación al navegador.
    function requestLocation() {
        const mainStatus = getElement('api-main-status');
        const message = getElement('api-location-message');
        const retryButton = getElement('api-location-retry');

        if (!navigator.geolocation) {
            mainStatus.textContent = 'Geolocalización no compatible';
            message.textContent =
                'Este navegador no permite obtener la ubicación.';

            retryButton.classList.remove('hidden');
            return;
        }

        mainStatus.textContent = 'Solicitando ubicación...';
        message.textContent =
            'Permite el acceso a la ubicación en el navegador.';

        navigator.geolocation.getCurrentPosition(
            async (position) => {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;

                retryButton.classList.add('hidden');
                mainStatus.textContent = 'Consultando ciudad y clima...';

                const results = await Promise.allSettled([
                    loadLocation(latitude, longitude),
                    loadWeather(latitude, longitude)
                ]);

                const successCount = results.filter(
                    result => result.status === 'fulfilled'
                ).length;

                mainStatus.textContent = successCount === 2
                    ? 'Ubicación y clima consultados'
                    : 'Consulta parcial: revisa los mensajes';

                if (successCount < 2) {
                    message.textContent =
                        'La ubicación fue detectada, pero una API no respondió.';
                }
            },
            (error) => {
                mainStatus.textContent = 'Ubicación no disponible';

                if (error.code === 1) {
                    message.textContent =
                        'Permiso denegado. Permite la ubicación en los ajustes del sitio.';
                } else if (error.code === 2) {
                    message.textContent =
                        'No se pudo determinar la ubicación del dispositivo.';
                } else {
                    message.textContent =
                        'La consulta de ubicación tardó demasiado. Inténtalo de nuevo.';
                }

                getElement('api-location-city').textContent =
                    'Ubicación no disponible';

                retryButton.classList.remove('hidden');
            },
            {
                enableHighAccuracy: false,
                timeout: 15000,
                maximumAge: 300000
            }
        );
    }

    getElement('api-location-retry')
        .addEventListener('click', requestLocation);

    // El tipo de cambio no depende de la ubicación.
    loadExchangeRate();

    // Obtiene las coordenadas y consulta ciudad, estado, país y clima.
    requestLocation();
})();
</script>