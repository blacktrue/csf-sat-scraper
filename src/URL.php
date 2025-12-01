<?php

declare(strict_types=1);

namespace Blacktrue\CsfSatScraper;

class URL
{
    public static string $thrower = 'https://wwwmat.sat.gob.mx/app/seg/faces/pages/lanzador.jsf'
        . '?url=/operacion/53027/genera-tu-constancia-de-situacion-fiscal'
        . '&tipoLogeo=c&target=principal&hostServer=https://wwwmat.sat.gob.mx';
    public static string $rfcampc = 'https://rfcampc.siat.sat.gob.mx';
    public static string $file = 'https://rfcampc.siat.sat.gob.mx/PTSC/IdcSiat/IdcGeneraConstancia.jsf';
    public static string $base = 'https://login.siat.sat.gob.mx';
}
