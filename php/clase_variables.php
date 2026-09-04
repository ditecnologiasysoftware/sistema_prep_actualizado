<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

class Variables
{
    public string $BaseDatos = '';
    public string $Servidor = '';
    public string $Usuario = '';
    public string $Clave = '';

    public function opcion($opc = 1): void
    {
        $mode = $_ENV['APP_ENV'] ?? 'local';
        $prefix = strtoupper($mode);

        $this->Servidor = $this->env($prefix . '_DB_HOST');
        $this->BaseDatos = $this->env($prefix . '_DB_DATABASE');
        $this->Usuario = $this->env($prefix . '_DB_USERNAME');
        $this->Clave = $_ENV[$prefix . '_DB_PASSWORD'] ?? '';
    }

    private function env(string $key): string
    {
        $value = $_ENV[$key] ?? '';
        if ($value === '') {
            throw new RuntimeException("Falta configurar la variable de entorno {$key}.");
        }
        return (string) $value;
    }
}
