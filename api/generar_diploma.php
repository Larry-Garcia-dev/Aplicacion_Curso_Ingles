<?php
// 1. Cargar el autoloader de Composer
require '../vendor/autoload.php';
// 2. Usar las clases de DomPDF
use Dompdf\Dompdf;
use Dompdf\Options;

// 3. Configurar opciones
$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'Helvetica');

// 4. Instanciar Dompdf
$dompdf = new Dompdf($options);

// 5. Validar que sea una solicitud POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido. Usa POST.']);
    exit;
}

// 6. Leer el JSON enviado
$jsonInput = file_get_contents('php://input');
$data = json_decode($jsonInput, true);

// 7. Validar el nombre
if (!isset($data['nombre']) || empty($data['nombre'])) {
    http_response_code(400);
    echo json_encode(['error' => 'El campo "nombre" es requerido.']);
    exit;
}

// 8. Sanitizar el nombre
$nombreAlumno = htmlspecialchars($data['nombre'], ENT_QUOTES, 'UTF-8');

try {
    // 9. Cargar el template HTML
    $html = file_get_contents('../views/diploma.html');

    // ==========================================================
    // NUEVO: Generar los datos de la fecha en español
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
    // ACTUALIZADO: Reemplazar todos los marcadores a la vez
    // ==========================================================
    
    // 10. Definir los marcadores y sus valores
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

    // 11. Reemplazar todos los marcadores en el HTML
    // str_replace puede tomar arrays, lo que es más limpio
    $html = str_replace($marcadores, $valores, $html);
    
    // 12. Cargar el HTML en DomPDF
    $dompdf->loadHtml($html);

    // 13. Configurar el papel (A4 horizontal)
    $dompdf->setPaper('A4', 'landscape');

    // 14. Renderizar el HTML como PDF
    $dompdf->render();

    // 15. Enviar el PDF al navegador
    $dompdf->stream('Diploma-'.$nombreAlumno.'.pdf', ["Attachment" => 0]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al generar el PDF: ' . $e->getMessage()]);
}

?>