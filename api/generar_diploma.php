<?php
// 1. Cargar el autoloader de Composer (subiendo un nivel)
require __DIR__ . '/../vendor/autoload.php';

// 2. Usar las clases de DomPDF
use Dompdf\Dompdf;
use Dompdf\Options;

// 3. Validar que sea una solicitud POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Método no permitido
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Método no permitido. Usa POST.']);
    exit;
}

// 4. Leer el JSON enviado
$jsonInput = file_get_contents('php://input');
$data = json_decode($jsonInput, true);

// 5. VALIDACIÓN (Solo para el nombre)
if (!$data || !isset($data['nombre']) || empty($data['nombre'])) {
    http_response_code(400); // Bad Request
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Falta data. El campo "nombre" es requerido en el JSON.']);
    exit;
}

// 6. Sanitizar el nombre
$nombreAlumno = htmlspecialchars($data['nombre'], ENT_QUOTES, 'UTF-8');

// ==========================================================
// NUEVO: Generar los datos de la fecha automáticamente
// ==========================================================
$dia = date('d');
$anio = date('Y');

// Array para convertir el número de mes a nombre en español
$meses = [
    '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril',
    '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto',
    '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
];

// Obtener el nombre del mes actual
$mes = $meses[date('m')];
// ==========================================================

// 7. Configurar opciones de DomPDF
$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'Helvetica');

// 8. Instanciar Dompdf
$dompdf = new Dompdf($options);

// 9. Iniciar el bloque try...catch
try {
    // 10. Cargar el template HTML (subiendo un nivel)
    $htmlPath = __DIR__ . 'diploma_template.html';
    if (!file_exists($htmlPath)) {
        throw new Exception("No se encontró el archivo diploma_template.html");
    }
    $html = file_get_contents($htmlPath);

    // 11. Definir los marcadores y sus valores
    $marcadores = [
        '{{NOMBRE_ALUMNO}}',
        '{{DIA}}',
        '{{MES}}',
        '{{ANIO}}'
    ];
    
    $valores = [
        $nombreAlumno,
        $dia,
        $mes,
        $anio
    ];

    // 12. Reemplazar todos los marcadores en el HTML
    $html = str_replace($marcadores, $valores, $html);
    
    // 13. Cargar el HTML en DomPDF
    $dompdf->loadHtml($html);

    // 14. Configurar el papel (A4 horizontal)
    $dompdf->setPaper('A4', 'landscape');

    // 15. Renderizar el HTML como PDF
    $dompdf->render();

    // 16. Enviar el PDF al navegador
    $dompdf->stream('Diploma-'.$nombreAlumno.'.pdf', ["Attachment" => 0]);

} catch (Exception $e) {
    // 17. Manejo de errores de generación
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error al generar el PDF: ' . $e->getMessage()]);
}

?>