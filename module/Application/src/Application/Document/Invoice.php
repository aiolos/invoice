<?php

namespace Application\Document;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * An invoice
 *
 * @ORM\Entity
 * @ORM\Table(name="invoices")
 * @ORM\HasLifecycleCallbacks
 * @property integer $id
 * @property integer $year
 * @property integer $owner
 * @property string $filename
 * @property date $createdAt
 * @property date $updatedAt
 *
 */
class Invoice implements ServiceManagerAwareInterface
{
    /**
     *
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     *
     * @var Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    protected function getInvoiceDate()
    {
        $dateTime = new \DateTime;
        $dateTime->setDate(2014, 4, 28);

        return $dateTime;
    }

    public function create($invoice)
    {
        switch (strtolower($invoice->getOwner()->getLanguage())) {
            case 'en':
            case 'gb':
                return $this->createEnglish($invoice);

                break;

            case 'de':
                return $this->createGerman($invoice);

                break;

            case 'nl':
            default:
                return $this->createDutch($invoice);

                break;
        }
    }

    protected function createDutch($invoice)
    {
        $alvDate = '25 april 2014';
        /* @var $owner Application\Entity\Owner */
        $owner = $invoice->getOwner();

        $ownerships = $owner->getObjectownerships();
        foreach ($ownerships as $ownership) {
            var_dump($ownership->getObject()->getId());
            switch ($ownership->getObject()->getId()) {
                case 1:
                    $boxName = $ownership->getName();
                    break;

                case 2:
                    $queryBuilder = $this->getEntityManager()->createQueryBuilder();
                    $queryBuilder
                        ->select('p')
                        ->from('Application\Entity\Powermeasurement', 'p')
                        ->where('p.objectId = ' . $ownership->getId())
                        ->andWhere('p.date > \'' . ($invoice->getYear() - 1) . '-01-01\'')
                        ->andWhere('p.date < \'' . $invoice->getYear() . '-12-31\'')
                        ->orderBy('p.date');
                    $powermeasurements = $queryBuilder->getQuery()->getResult();

                    $measurements = array();
                    foreach ($powermeasurements as $powerMeasurement) {
                        $measurements[$powerMeasurement->getDate()->format('Y')] = array('date' => $powerMeasurement->getDate(), 'value' => $powerMeasurement->getValue());
                    }
                    break;

                default:
                    break;
            }
        }

        $prices = $this->getEntityManager()->getRepository('Application\Entity\Price')->findBy(array('year' => $invoice->getYear()));

        foreach ($prices as $price) {
            switch ($price->getId()->getId()) {
                case 1:
                    /* Box */
                    $boxPrice = $price->getPrice();
                    break;
                case 2:
                    /* Power */
                    $powerPrice = $price->getPrice();
                    break;
                case 3:
                    /* Winter */
                    $winterPrice = $price->getPrice();
                    break;

                default:
                    break;
            }
        }
        // Create new image object
        $imageFile = 'public/img/logo.png';
        $stampImage = \ZendPdf\Image::imageWithPath($imageFile);

        // Create new PDF if file doesn't exist
        $pdf = new \ZendPdf\PdfDocument();
        // Create new Style
        $style = new \ZendPdf\Style();
        $style->setFillColor(new \ZendPdf\Color\Rgb(0, 0, 0.9));
        $style->setLineColor(new \ZendPdf\Color\GrayScale(0.2));
        $style->setLineWidth(3);
        $style->setLineDashingPattern(array(3, 2, 3, 4), 1.6);
        $fontH = \ZendPdf\Font::fontWithName(\ZendPdf\Font::FONT_HELVETICA_BOLD);
        $style->setFont($fontH, 32);

        $pdf->pages[] = ($page1 = $pdf->newPage('A4'));

        $page1->drawImage($stampImage, 50, 750, 200, 800);
        // Create new font
        $font = \ZendPdf\Font::fontWithName(\ZendPdf\Font::FONT_HELVETICA);
        $fontSize = 12;
        $fontBold = \ZendPdf\Font::fontWithName(\ZendPdf\Font::FONT_HELVETICA_BOLD);
        // Apply font and draw text
        $page1->setFont($font, 10)
            ->setFillColor(\ZendPdf\Color\Html::color('#9999cc'))
            ->drawText('KVK Nederland XXXXXX', 50, 740)
            ->drawText('BTW nr.: NL XXXX.XX.XXX', 50, 730)
            ->drawText('Naam', 50, 720)
            ->drawText('Penningmeester', 50, 710)
            ->drawText('Straat 123', 50, 700)
            ->drawText('1234 AB Plaats', 50, 690)
            ->drawText('Tel 012-1234567', 50, 680)
            ->drawText('penningmeester@example.com', 50, 670)
            ;

        $page1->setFont($font, $fontSize)
            ->setFillColor(\ZendPdf\Color\Html::color('#000000'))
            ->drawText($owner->getInitials() . ' ' . $owner->getName(), 300, 740)
            ->drawText($owner->getStreet() . ' ' . $owner->getHouseNumber(), 300, 726)
            ->drawText($owner->getPostalcode() . ' ' . $owner->getCity(), 300, 712)
            ;
        if (strtolower($owner->getCountry()) == 'de') {
            $page1->drawText('Duitsland', 300, 704);
        }

        $formatter = new \IntlDateFormatter('nl_NL', \IntlDateFormatter::LONG, \IntlDateFormatter::NONE);
        $text = 'Plaats, ' . $formatter->format($this->getInvoiceDate());
        $textWidth = \Application\Controller\Plugin\Font::getTextWidth($text, $page1, $fontSize);
        $page1->drawText($text,
            $page1->getWidth() - 50 - $textWidth - $fontSize,
            670
        );

        $page1->setFont($fontBold, 12)
            ->setFillColor(\ZendPdf\Color\Html::color('#000000'))
            ->drawText('Rekening', 50, 640);

    switch ($owner->getGender()) {
        case 'M':
            $salutation = 'heer';
            break;

        case 'F':
            $salutation = 'mevrouw';
            break;

        default:
            $salutation = 'heer/mevrouw';
            break;
    }
        $page1->setFont($font, 12)
            ->setFillColor(\ZendPdf\Color\Html::color('#000000'))
            ->drawText(
                'Geachte ' . $salutation . ' ' . $owner->getInitials() . ' ' . $owner->getName() . ',',
                50,
                610
            );

        $page1->drawText('Conform het besluit van de algemene ledenvergadering van ', 50, 585);
        $page1->drawText($alvDate . ' is de jaarlijkse bijdrage ' . $invoice->getYear() . ' vastgesteld op:', 50, 570);
        $page1->drawText('€', 470, 570, 'utf-8');

        $text = number_format($boxPrice, 2, ',', '.');
        $textWidth = \Application\Controller\Plugin\Font::getTextWidth($text, $page1, $fontSize);
        $page1->drawText($text,
                $page1->getWidth() - 50 - $textWidth - $fontSize,
                570);

        $page1->drawText('Uw elektraverbruik ' . ($invoice->getYear() - 1) . '/' . $invoice->getYear() . ' bedroeg volgens meteropname:', 50, 545);

        $page1->drawText('Stand ' . $measurements[$invoice->getYear()]['date']->format('d-m-Y') . ':', 220, 528);
        $valueThisYear = $measurements[$invoice->getYear()]['value'];
        $textWidth = \Application\Controller\Plugin\Font::getTextWidth($valueThisYear, $page1, $fontSize);
        $page1->drawText($valueThisYear,
            $page1->getWidth() - 210 - $textWidth - $fontSize,
            528
        );
        //$page1->drawText('4537', 340, 528);
        $page1->drawText('kWh', 380, 528);

        $page1->drawText('Stand ' . $measurements[($invoice->getYear() - 1)]['date']->format('d-m-Y') . ':', 220, 510);
        $valueLastYear = $measurements[($invoice->getYear() - 1)]['value'];
        $textWidth = \Application\Controller\Plugin\Font::getTextWidth($valueLastYear, $page1, $fontSize);
        $page1->drawText($valueLastYear,
            $page1->getWidth() - 210 - $textWidth - $fontSize,
            510
        );
        //$page1->drawText('4212', 340, 510);
        $page1->drawText('kWh', 380, 510);

        // Draw line
        $page1->setLineWidth(0.5)
            ->drawLine(330, 505, 375, 505);

        $page1->drawText('Verbruik na aftrek van 10 KWh als vrije drempel:', 50, 490);
        $powerUsage = $valueThisYear - $valueLastYear - 10;
        if ($powerUsage < 0) {
            $powerUsage = 0;
        }
        $textWidth = \Application\Controller\Plugin\Font::getTextWidth($powerUsage, $page1, $fontSize);
        $page1->drawText($powerUsage,
            $page1->getWidth() - 210 - $textWidth - $fontSize,
            490
        );

        $page1->drawText('kWh', 380, 490);

        $page1->drawText('kWh-prijs ' . $invoice->getYear() . ':', 220, 470);
        $text = '€ 0,' . $powerPrice;
        $textWidth = \Application\Controller\Plugin\Font::getTextWidth($text, $page1, $fontSize);
        $page1->drawText($text,
            $page1->getWidth() - 210 - $textWidth - $fontSize,
            470,
            'utf-8'
        );

        $page1->drawText('Kosten verbruik incl. 21% btw:', 220, 450);
        $page1->drawText('€', 470, 450, 'utf-8');
        $powerUsagePrice = $powerUsage * $powerPrice / 100;
        $textWidth = \Application\Controller\Plugin\Font::getTextWidth(number_format($powerUsagePrice, 2, ',', '.'), $page1, $fontSize);
        $page1->drawText(number_format($powerUsagePrice, 2, ',', '.'),
            $page1->getWidth() - 50 - $textWidth - $fontSize,
            450
        );

        // Draw line
        $page1->setLineWidth(0.5)
            ->drawLine(470, 440, $page1->getWidth() - 60, 440);

        $page1->setFont($fontBold, 12);
        $page1->drawText('Door U te voldoen:', 220, 425);
        $page1->drawText('€', 470, 425, 'utf-8');
        $totalPrice = $powerUsagePrice + $boxPrice;
        $textWidth = \Application\Controller\Plugin\Font::getTextWidth(number_format($totalPrice, 2, ',', '.'), $page1, $fontSize);
        $page1->drawText(number_format($totalPrice, 2, ',', '.'),
                $page1->getWidth() - 50 - $textWidth - $fontSize,
                425);

        // Draw line
        $page1->setLineWidth(0.5)
            ->drawLine(50, 380, $page1->getWidth() - 60, 380);

        $page1->setFont($font, 12);
        $page1->drawText('Ik verzoek U dit bedrag binnen 2 weken over te maken op rekeningnummer: xx.xx.xx.xxx', 50, 350);
        $page1->drawText('t.n.v. vereniging onder vermelding \'Bijdrage servicekosten ' . $boxName . '\'.', 50, 335);
        //$page1->drawText('', 50, 320);
        $page1->drawText('IBAN:', 50, 300);
        $page1->drawText('NL12 BANK 1234 5678 90', 85, 300);
        $page1->drawText('BIC:', 50, 285);
        $page1->drawText('BANK NL2A', 85, 285);
        $page1->drawText('Met vriendelijke Groeten,', 50, 250);
        $page1->drawText('...,', 50, 225);
        $page1->drawText('Penningmeester', 50, 210);

        return $pdf;
    }

    protected function createGerman($invoice)
    {
        $alvDate = '25 apr. 2014';
        /* @var $owner Application\Entity\Owner */
        $owner = $invoice->getOwner();

        $ownerships = $owner->getObjectownerships();
        foreach ($ownerships as $ownership) {
            var_dump($ownership->getObject()->getId());
            switch ($ownership->getObject()->getId()) {
                case 1:
                    $boxName = $ownership->getName();
                    break;

                case 2:
                    $queryBuilder = $this->getEntityManager()->createQueryBuilder();
                    $queryBuilder
                        ->select('p')
                        ->from('Application\Entity\Powermeasurement', 'p')
                        ->where('p.objectId = ' . $ownership->getId())
                        ->andWhere('p.date > \'' . ($invoice->getYear() - 1) . '-01-01\'')
                        ->andWhere('p.date < \'' . $invoice->getYear() . '-12-31\'')
                        ->orderBy('p.date');
                    $powermeasurements = $queryBuilder->getQuery()->getResult();

                    $measurements = array();
                    foreach ($powermeasurements as $powerMeasurement) {
                        $measurements[$powerMeasurement->getDate()->format('Y')] = array('date' => $powerMeasurement->getDate(), 'value' => $powerMeasurement->getValue());
                    }
                    break;

                default:
                    break;
            }
        }

        $prices = $this->getEntityManager()->getRepository('Application\Entity\Price')->findBy(array('year' => $invoice->getYear()));

        foreach ($prices as $price) {
            switch ($price->getId()->getId()) {
                case 1:
                    /* Box */
                    $boxPrice = $price->getPrice();
                    break;
                case 2:
                    /* Power */
                    $powerPrice = $price->getPrice();
                    break;
                case 3:
                    /* Winter */
                    $winterPrice = $price->getPrice();
                    break;

                default:
                    break;
            }
        }
        // Create new image object
        $imageFile = 'public/img/logo.png';
        $stampImage = \ZendPdf\Image::imageWithPath($imageFile);

        // Create new PDF if file doesn't exist
        $pdf = new \ZendPdf\PdfDocument();
        // Create new Style
        $style = new \ZendPdf\Style();
        $style->setFillColor(new \ZendPdf\Color\Rgb(0, 0, 0.9));
        $style->setLineColor(new \ZendPdf\Color\GrayScale(0.2));
        $style->setLineWidth(3);
        $style->setLineDashingPattern(array(3, 2, 3, 4), 1.6);
        $fontH = \ZendPdf\Font::fontWithName(\ZendPdf\Font::FONT_HELVETICA_BOLD);
        $style->setFont($fontH, 32);

        $pdf->pages[] = ($page1 = $pdf->newPage('A4'));

        $page1->drawImage($stampImage, 50, 750, 200, 800);
        // Create new font
        $font = \ZendPdf\Font::fontWithName(\ZendPdf\Font::FONT_HELVETICA);
        $fontSize = 12;
        $fontBold = \ZendPdf\Font::fontWithName(\ZendPdf\Font::FONT_HELVETICA_BOLD);
        // Apply font and draw text
        $page1->setFont($font, 10)
            ->setFillColor(\ZendPdf\Color\Html::color('#9999cc'))
            ->drawText('KVK Nederland xxxxxxxx', 50, 740)
            ->drawText('BTW nr.: NL xxxx.xx.xxx', 50, 730)
            ->drawText('Naam', 50, 720)
            ->drawText('Schatzmeister', 50, 710)
            ->drawText('Strasse 19', 50, 700)
            ->drawText('PLZ Platz', 50, 690)
            ->drawText('Tel 010-1234567', 50, 680)
            ->drawText('schatzmeister@example.com', 50, 670)
            ;

        $page1->setFont($font, $fontSize)
            ->setFillColor(\ZendPdf\Color\Html::color('#000000'))
            ->drawText($owner->getInitials() . ' ' . $owner->getName(), 300, 740, 'utf-8')
            ->drawText($owner->getStreet() . ' ' . $owner->getHouseNumber(), 300, 726, 'utf-8')
            ->drawText($owner->getPostalcode() . ' ' . $owner->getCity(), 300, 712, 'utf-8')
            ;
        if (strtolower($owner->getCountry()) == 'de') {
            $page1->drawText('Duitsland', 300, 704);
        }

        $formatter = new \IntlDateFormatter('de_DE', \IntlDateFormatter::LONG, \IntlDateFormatter::NONE);
        $text = 'Platz, ' . $formatter->format($this->getInvoiceDate());
        $textWidth = \Application\Controller\Plugin\Font::getTextWidth($text, $page1, $fontSize);
        $page1->drawText($text,
            $page1->getWidth() - 50 - $textWidth - $fontSize,
            670
        );

        $page1->setFont($fontBold, 12)
            ->setFillColor(\ZendPdf\Color\Html::color('#000000'))
            ->drawText('Servicebeitrag', 50, 640);

    switch ($owner->getGender()) {
        case 'M':
            $salutation = 'Herrn';
            break;

        case 'F':
            $salutation = 'Frau';
            break;

        default:
            $salutation = 'Herrn/Frau';
            break;
    }
        $page1->setFont($font, 12)
            ->setFillColor(\ZendPdf\Color\Html::color('#000000'))
            ->drawText(
                'Sehr geehrter ' . $salutation . ' ' . $owner->getInitials() . ' ' . $owner->getName() . ',',
                50,
                610,
                'utf-8'
            );

        $page1->drawText('Hiermit berechnen wir Ihnen den Anteil des Servicebetrages für 2014,', 50, 585, 'utf-8');
        $page1->drawText('nach dem Beschluss der Jahreshauptversammlung vom ' . $alvDate . ':', 50, 570, 'utf-8');
        $page1->drawText('€', 470, 570, 'utf-8');

        $text = number_format($boxPrice, 2, ',', '.');
        $textWidth = \Application\Controller\Plugin\Font::getTextWidth($text, $page1, $fontSize);
        $page1->drawText($text,
                $page1->getWidth() - 50 - $textWidth - $fontSize,
                570);

        $page1->drawText('Stromverbrauch ' . ($invoice->getYear() - 1) . '/' . $invoice->getYear() . ':', 50, 545);

        $page1->drawText('Stand ' . $measurements[$invoice->getYear()]['date']->format('d-m-Y') . ':', 220, 528);
        $valueThisYear = $measurements[$invoice->getYear()]['value'];
        $textWidth = \Application\Controller\Plugin\Font::getTextWidth($valueThisYear, $page1, $fontSize);
        $page1->drawText($valueThisYear,
            $page1->getWidth() - 210 - $textWidth - $fontSize,
            528
        );
        //$page1->drawText('4537', 340, 528);
        $page1->drawText('kWh', 380, 528);

        $page1->drawText('Stand ' . $measurements[($invoice->getYear() - 1)]['date']->format('d-m-Y') . ':', 220, 510);
        $valueLastYear = $measurements[($invoice->getYear() - 1)]['value'];
        $textWidth = \Application\Controller\Plugin\Font::getTextWidth($valueLastYear, $page1, $fontSize);
        $page1->drawText($valueLastYear,
            $page1->getWidth() - 210 - $textWidth - $fontSize,
            510
        );
        //$page1->drawText('4212', 340, 510);
        $page1->drawText('kWh', 380, 510);

        // Draw line
        $page1->setLineWidth(0.5)
            ->drawLine(330, 505, 375, 505);

        $page1->drawText('abzügl. 10 KWh Freimenge /Mehrverbrauch:', 50, 490, 'utf-8');
        $powerUsage = $valueThisYear - $valueLastYear - 10;
        if ($powerUsage < 0) {
            $powerUsage = 0;
        }
        $textWidth = \Application\Controller\Plugin\Font::getTextWidth($powerUsage, $page1, $fontSize);
        $page1->drawText($powerUsage,
            $page1->getWidth() - 210 - $textWidth - $fontSize,
            490
        );

        $page1->drawText('kWh', 380, 490);

        $page1->drawText('kWh-preis ' . $invoice->getYear() . ':', 220, 470);
        $text = '€ 0,' . $powerPrice;
        $textWidth = \Application\Controller\Plugin\Font::getTextWidth($text, $page1, $fontSize);
        $page1->drawText($text,
            $page1->getWidth() - 210 - $textWidth - $fontSize,
            470,
            'utf-8'
        );

        $page1->drawText('Gesamt incl. 21% mwst:', 220, 450);
        $page1->drawText('€', 470, 450, 'utf-8');
        $powerUsagePrice = $powerUsage * $powerPrice / 100;
        $textWidth = \Application\Controller\Plugin\Font::getTextWidth(number_format($powerUsagePrice, 2, ',', '.'), $page1, $fontSize);
        $page1->drawText(number_format($powerUsagePrice, 2, ',', '.'),
            $page1->getWidth() - 50 - $textWidth - $fontSize,
            450
        );

        // Draw line
        $page1->setLineWidth(0.5)
            ->drawLine(470, 440, $page1->getWidth() - 60, 440);

        $page1->setFont($fontBold, 12);
        $page1->drawText('Gesamtbetrag:', 220, 425);
        $page1->drawText('€', 470, 425, 'utf-8');
        $totalPrice = $powerUsagePrice + $boxPrice;
        $textWidth = \Application\Controller\Plugin\Font::getTextWidth(number_format($totalPrice, 2, ',', '.'), $page1, $fontSize);
        $page1->drawText(number_format($totalPrice, 2, ',', '.'),
                $page1->getWidth() - 50 - $textWidth - $fontSize,
                425);

        // Draw line
        $page1->setLineWidth(0.5)
            ->drawLine(50, 380, $page1->getWidth() - 60, 380);

        $page1->setFont($font, 12);
        $page1->drawText('Wir bitten Sie, den Betrag innerhalb von 14 Tagen auf das Konto', 50, 350, 'utf-8');
        $page1->drawText('xx.xx.xx.xxx der ABN-AMRO Bank, auf den Namen von "Vereinigung"', 50, 335, 'utf-8');
        $page1->drawText('mit der Angabe Ihrer Liegeplatznummer \'' . $boxName . '\' zu überweisen.', 50, 320, 'utf-8');
        //$page1->drawText('', 50, 320);
        $page1->drawText('IBAN:', 50, 295);
        $page1->drawText('NL98 ABNA xx xxxx xxxx', 85, 295);
        $page1->drawText('BIC:', 50, 280);
        $page1->drawText('ABNA NL2A', 85, 280);
        $page1->drawText('Bankschecks werden nicht akzeptiert.', 50, 265);
        $page1->drawText('Mit freundlichen Grüßen,', 50, 240, 'utf-8');
        $page1->drawText('Nahme,', 50, 215);
        $page1->drawText('Schatzmeister', 50, 200);

        return $pdf;
    }

    protected function createEnglish($invoice)
    {
        $alvDate = 'April, 25 2014';
        /* @var $owner Application\Entity\Owner */
        $owner = $invoice->getOwner();

        $ownerships = $owner->getObjectownerships();
        foreach ($ownerships as $ownership) {
            var_dump($ownership->getObject()->getId());
            switch ($ownership->getObject()->getId()) {
                case 1:
                    $boxName = $ownership->getName();
                    break;

                case 2:
                    $queryBuilder = $this->getEntityManager()->createQueryBuilder();
                    $queryBuilder
                        ->select('p')
                        ->from('Application\Entity\Powermeasurement', 'p')
                        ->where('p.objectId = ' . $ownership->getId())
                        ->andWhere('p.date > \'' . ($invoice->getYear() - 1) . '-01-01\'')
                        ->andWhere('p.date < \'' . $invoice->getYear() . '-12-31\'')
                        ->orderBy('p.date');
                    $powermeasurements = $queryBuilder->getQuery()->getResult();

                    $measurements = array();
                    foreach ($powermeasurements as $powerMeasurement) {
                        $measurements[$powerMeasurement->getDate()->format('Y')] = array('date' => $powerMeasurement->getDate(), 'value' => $powerMeasurement->getValue());
                    }
                    break;

                default:
                    break;
            }
        }

        $prices = $this->getEntityManager()->getRepository('Application\Entity\Price')->findBy(array('year' => $invoice->getYear()));

        foreach ($prices as $price) {
            switch ($price->getId()->getId()) {
                case 1:
                    /* Box */
                    $boxPrice = $price->getPrice();
                    break;
                case 2:
                    /* Power */
                    $powerPrice = $price->getPrice();
                    break;
                case 3:
                    /* Winter */
                    $winterPrice = $price->getPrice();
                    break;

                default:
                    break;
            }
        }
        // Create new image object
        $imageFile = 'public/img/logo.png';
        $stampImage = \ZendPdf\Image::imageWithPath($imageFile);

        // Create new PDF if file doesn't exist
        $pdf = new \ZendPdf\PdfDocument();
        // Create new Style
        $style = new \ZendPdf\Style();
        $style->setFillColor(new \ZendPdf\Color\Rgb(0, 0, 0.9));
        $style->setLineColor(new \ZendPdf\Color\GrayScale(0.2));
        $style->setLineWidth(3);
        $style->setLineDashingPattern(array(3, 2, 3, 4), 1.6);
        $fontH = \ZendPdf\Font::fontWithName(\ZendPdf\Font::FONT_HELVETICA_BOLD);
        $style->setFont($fontH, 32);

        $pdf->pages[] = ($page1 = $pdf->newPage('A4'));

        $page1->drawImage($stampImage, 50, 750, 200, 800);
        // Create new font
        $font = \ZendPdf\Font::fontWithName(\ZendPdf\Font::FONT_HELVETICA);
        $fontSize = 12;
        $fontBold = \ZendPdf\Font::fontWithName(\ZendPdf\Font::FONT_HELVETICA_BOLD);
        // Apply font and draw text
        $page1->setFont($font, 10)
            ->setFillColor(\ZendPdf\Color\Html::color('#9999cc'))
            ->drawText('KVK Nederland xxxxxxx', 50, 740)
            ->drawText('BTW nr.: NL xxxx.xx.xxx', 50, 730)
            ->drawText('Name', 50, 720)
            ->drawText('Treasurer', 50, 710)
            ->drawText('Street 123', 50, 700)
            ->drawText('1234 AB City', 50, 690)
            ->drawText('Tel 010-1234567', 50, 680)
            ->drawText('treasurer@example.com', 50, 670)
            ;

        $page1->setFont($font, $fontSize)
            ->setFillColor(\ZendPdf\Color\Html::color('#000000'))
            ->drawText($owner->getInitials() . ' ' . $owner->getName(), 300, 740)
            ->drawText($owner->getStreet() . ' ' . $owner->getHouseNumber(), 300, 726)
            ->drawText($owner->getPostalcode() . ' ' . $owner->getCity(), 300, 712)
            ;
        if (strtolower($owner->getCountry()) == 'en') {
            $page1->drawText('(GB)', 300, 704);
        }

        $formatter = new \IntlDateFormatter('en_GB', \IntlDateFormatter::LONG, \IntlDateFormatter::NONE);
        $text = 'City, ' . $formatter->format($this->getInvoiceDate());
        $textWidth = \Application\Controller\Plugin\Font::getTextWidth($text, $page1, $fontSize);
        $page1->drawText($text,
            $page1->getWidth() - 50 - $textWidth - $fontSize,
            670
        );

        $page1->setFont($fontBold, 12)
            ->setFillColor(\ZendPdf\Color\Html::color('#000000'))
            ->drawText('Invoice', 50, 640);

    switch ($owner->getGender()) {
        case 'M':
            $salutation = 'Mr.';
            break;

        case 'F':
            $salutation = 'Mrs.';
            break;

        default:
            $salutation = 'Mr./Mrs.';
            break;
    }
        $page1->setFont($font, 12)
            ->setFillColor(\ZendPdf\Color\Html::color('#000000'))
            ->drawText(
                'Dear ' . $salutation . ' ' . $owner->getInitials() . ' ' . $owner->getName() . ',',
                50,
                610
            );

        $page1->drawText('In accordance with the decision of the General Meeting of ' . $alvDate, 50, 585);
        $page1->drawText('is the annual contribution in 2014 set at:', 50, 570);
        $page1->drawText('€', 470, 570, 'utf-8');

        $text = number_format($boxPrice, 2, ',', '.');
        $textWidth = \Application\Controller\Plugin\Font::getTextWidth($text, $page1, $fontSize);
        $page1->drawText($text,
                $page1->getWidth() - 50 - $textWidth - $fontSize,
                570);

        $page1->drawText('Your electricity consumption ' . ($invoice->getYear() - 1) . '/' . $invoice->getYear() . ' was, according to meter reading:', 50, 545);

        $page1->drawText('Position ' . $measurements[$invoice->getYear()]['date']->format('d-m-Y') . ':', 220, 528);
        $valueThisYear = $measurements[$invoice->getYear()]['value'];
        $textWidth = \Application\Controller\Plugin\Font::getTextWidth($valueThisYear, $page1, $fontSize);
        $page1->drawText($valueThisYear,
            $page1->getWidth() - 210 - $textWidth - $fontSize,
            528
        );
        //$page1->drawText('4537', 340, 528);
        $page1->drawText('kWh', 380, 528);

        $page1->drawText('Position ' . $measurements[($invoice->getYear() - 1)]['date']->format('d-m-Y') . ':', 220, 510);
        $valueLastYear = $measurements[($invoice->getYear() - 1)]['value'];
        $textWidth = \Application\Controller\Plugin\Font::getTextWidth($valueLastYear, $page1, $fontSize);
        $page1->drawText($valueLastYear,
            $page1->getWidth() - 210 - $textWidth - $fontSize,
            510
        );
        //$page1->drawText('4212', 340, 510);
        $page1->drawText('kWh', 380, 510);

        // Draw line
        $page1->setLineWidth(0.5)
            ->drawLine(330, 505, 375, 505);

        $page1->drawText('Consumption after 10 KWh as free threshold:', 50, 490);
        $powerUsage = $valueThisYear - $valueLastYear - 10;
        if ($powerUsage < 0) {
            $powerUsage = 0;
        }
        $textWidth = \Application\Controller\Plugin\Font::getTextWidth($powerUsage, $page1, $fontSize);
        $page1->drawText($powerUsage,
            $page1->getWidth() - 210 - $textWidth - $fontSize,
            490
        );

        $page1->drawText('kWh', 380, 490);

        $page1->drawText('kWh price ' . $invoice->getYear() . ':', 220, 470);
        $text = '€ 0,' . $powerPrice;
        $textWidth = \Application\Controller\Plugin\Font::getTextWidth($text, $page1, $fontSize);
        $page1->drawText($text,
            $page1->getWidth() - 210 - $textWidth - $fontSize,
            470,
            'utf-8'
        );

        $page1->drawText('Total price incl. 21% VAT:', 220, 450);
        $page1->drawText('€', 470, 450, 'utf-8');
        $powerUsagePrice = $powerUsage * $powerPrice / 100;
        $textWidth = \Application\Controller\Plugin\Font::getTextWidth(number_format($powerUsagePrice, 2, ',', '.'), $page1, $fontSize);
        $page1->drawText(number_format($powerUsagePrice, 2, ',', '.'),
            $page1->getWidth() - 50 - $textWidth - $fontSize,
            450
        );

        // Draw line
        $page1->setLineWidth(0.5)
            ->drawLine(470, 440, $page1->getWidth() - 60, 440);

        $page1->setFont($fontBold, 12);
        $page1->drawText('Your total dept:', 220, 425);
        $page1->drawText('€', 470, 425, 'utf-8');
        $totalPrice = $powerUsagePrice + $boxPrice;
        $textWidth = \Application\Controller\Plugin\Font::getTextWidth(number_format($totalPrice, 2, ',', '.'), $page1, $fontSize);
        $page1->drawText(number_format($totalPrice, 2, ',', '.'),
                $page1->getWidth() - 50 - $textWidth - $fontSize,
                425);

        // Draw line
        $page1->setLineWidth(0.5)
            ->drawLine(50, 380, $page1->getWidth() - 60, 380);

        $page1->setFont($font, 12);
        $page1->drawText('Payment should be made within 14 days by money transfer to the account', 50, 350);
        $page1->drawText('xx.xx.xx.xx from ABN-AMRO Bank, in the name of \'Organisation\' with', 50, 335);
        $page1->drawText('reference \'service charges box no. ' . $boxName . '\'.', 50, 320);
        //$page1->drawText('', 50, 320);
        $page1->drawText('IBAN:', 50, 300);
        $page1->drawText('NL98 ABNA xx xxxx xxxx', 85, 300);
        $page1->drawText('BIC:', 50, 285);
        $page1->drawText('ABNA NL2A', 85, 285);
        $page1->drawText('With kind regards,', 50, 250);
        $page1->drawText('Name,', 50, 225);
        $page1->drawText('Treasurer', 50, 210);

        return $pdf;
    }

    /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Set service manager instance
     *
     * @param ServiceManager $serviceManager
     * @return Mobidesk
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    /**
     * Get the entity manager
     * @return Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        if (!$this->entityManager) {
            $this->entityManager = $this->getServiceManager()->get('Doctrine\ORM\EntityManager');
        }
        return $this->entityManager;
    }

    /**
     * Get the entity manager
     * @param Doctrine\ORM\EntityManager $entityManager
     * @return Application\Service\Mobidesk
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }
}
