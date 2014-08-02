<?php
namespace boundstate\htmlconverter;

use yii\helpers\ArrayHelper;

/**
 * HtmlToPdfConverter converts HTML content to PDF using wkhtmtopdf.
 * @link http://wkhtmltopdf.org/
 *
 * It is used by [[PdfResponseFormatter]] to format response data.
 *
 * @author Bound State Software <info@boundstatesoftware.com>
 */
class HtmlToPdfConverter extends BaseConverter
{
    /**
     * @var string path to the wkhtmltopdf binary
     */
    public $bin = '/usr/bin/wkhtmltopdf';

    /**
     * Converts HTML to a PDF file.
     * @param string $html HTML
     * @param array $options
     * @return string PDF file contents
     */
    public function convert($html, $options = [])
    {
        // Override any global options with locally specified options
        $options = ArrayHelper::merge($this->options, $options);

        // Generate temp HTML file
        $htmlFilename = $this->getTempFilename('html');
        $this->createHtmlFile($html, $htmlFilename);

        // Generate temp PDF file and get contents
        $pdfFilename = $this->getTempFilename('pdf');
        $this->runCommand($htmlFilename, $pdfFilename, $options);
        $data = @file_get_contents($pdfFilename);

        // Cleanup
        @unlink($htmlFilename);
        @unlink($pdfFilename);

        return $data;
    }
}