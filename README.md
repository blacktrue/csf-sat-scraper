# CSF SAT Scraper

Un scraper en PHP para descargar constancias de situación fiscal del SAT México.

## 🚀 Inicio Rápido

### Instalación

```bash
composer install blacktrue/csf-sat-scraper
```

### Uso Básico

```php
<?php

require 'vendor/autoload.php';

use Blacktrue\CsfSatScraper\Scraper;
use Blacktrue\CsfSatScraper\HttpClientFactory;
use PhpCfdi\ImageCaptchaResolver\Resolvers\ConsoleResolver;

$client = HttpClientFactory::create([
    'curl' => [
        CURLOPT_SSL_CIPHER_LIST => 'DEFAULT@SECLEVEL=1',
    ],
    RequestOptions::VERIFY => false,
]);

$captchaSolver = new ConsoleResolver();

$scraper = new Scraper(
    $client,
    $captchaSolver,
    'TU_RFC',
    'TU_CONTRASEÑA'
);

$pdfContent = $scraper->download();
file_put_contents('constancia.pdf', $pdfContent);
```

## 🧪 Testing

### Ejecutar Tests

```bash
# Todos los tests
composer test

# Con formato legible
vendor/bin/phpunit --testdox

# Sin cobertura (más rápido)
vendor/bin/phpunit --no-coverage

# Test específico
vendor/bin/phpunit tests/Unit/Services/CaptchaServiceTest.php
```

### Cobertura de Código

```bash
composer test-coverage
open coverage/index.html
```

## 🛠️ Desarrollo

### Requisitos

- PHP 8.4+
- Composer
- Extensión cURL

### Dependencias Principales

- `guzzlehttp/guzzle` - Cliente HTTP
- `symfony/dom-crawler` - Parsing de HTML
- `phpcfdi/image-captcha-resolver` - Resolución de captchas

### Dependencias de Desarrollo

- `phpunit/phpunit` ^10.0 - Framework de testing

## 🔧 Servicios

### AuthenticationService

Maneja todo el proceso de autenticación:
- Inicialización de login
- Obtención del formulario
- Envío de credenciales
- Verificación de sesión

### CaptchaService

Resuelve el captcha del SAT:
- Extracción de imagen del HTML
- Resolución con el solver configurado

### SSOHandler

Gestiona el flujo SSO/SAML:
- Procesamiento de formularios SAML
- Manejo de iframes
- Redirecciones SSO

### DocumentService

Descarga el documento:
- Envío de formulario final
- Descarga del PDF

## 🤝 Contribuir

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

### Ejecutar Tests Antes de PR

```bash
composer test
```

## 📝 Licencia

MIT License

## 👤 Autor

Cesar Aguilera - cesargnu29@gmail.com

## 🙏 Agradecimientos

- PhpCfdi por image-captcha-resolver
- Comunidad de PHP por las herramientas

