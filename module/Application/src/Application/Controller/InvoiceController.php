<?php
/**
 * Invoice Controller
 *
 * @copyright 2013 Henri de Jong
 * @author Henri de Jong <henridejong@gmail.com>
 */

namespace Application\Controller;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Mail;
use Zend\Mime\Mime;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Application\Form\InvoiceForm;
use Doctrine\ORM\EntityManager;
use Application\Entity\Invoice;

class InvoiceController extends AbstractActionController //implements ServiceManagerAwareInterface
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->em;
    }

    public function indexAction()
    {
        return new ViewModel();
    }

    public function listAction()
    {
        /* @todo something has to be done with the start and limit */

        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder
            ->select('i')
            ->from('Application\Entity\Invoice', 'i')
            ->setFirstResult($this->params()->fromQuery('iDisplayStart', 0))
            ->setMaxResults($this->params()->fromQuery('iDisplayLength', 5));;

        $invoices = $queryBuilder->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
        $totalInvoices = $queryBuilder->select('count(i)')->setFirstResult(0)->getQuery()->getSingleScalarResult();

        return new JsonModel(array(
            'invoices' => $invoices,
            'iTotalRecords' => $totalInvoices,
            'iTotalDisplayRecords' => $totalInvoices
        ));
    }

    public function sendAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('owner');
        }

        /* @var $invoice Application\Entity\Invoice */
        $invoice = $this->getEntityManager()->find('Application\Entity\Invoice', (int) $this->params()->fromRoute('id'));

        if (!file_exists('/tmp/pdf/' . $invoice->getFilename())) {
            $this->createPdf();
        }

        $pdf = \ZendPdf\PdfDocument::load('/tmp/pdf/' . $invoice->getFilename(), 1);
        $fileContents = $pdf->render();

        $response = $this->getResponse();
        $response->setContent($fileContents);

        $salutation = $this->getSalutation($invoice->getOwner());

        $this->renderer = $this->getServiceLocator()->get('ViewRenderer');
        $content = $this->renderer->render('email/invoice.phtml', array('invoice' => $invoice, 'salutation' => $salutation, 'path' => realpath('.')));
        $contentTxt = $this->renderer->render('email/invoice.txt.' . strtolower($invoice->getOwner()->getLanguage()) . '.phtml', array('invoice' => $invoice, 'salutation' => $salutation, 'path' => realpath('.')));

        $pdfPart = new MimePart($fileContents);
        $pdfPart->type = 'application/pdf';
        $pdfPart->disposition = Mime::DISPOSITION_ATTACHMENT;
        $pdfPart->encoding = Mime::ENCODING_BASE64;
        $pdfPart->filename = $invoice->getFilename();

        // make a header as html
        $text = new MimePart($contentTxt);
        $text->type = "text/plain";

        /* Fetch the logo */
        $multiPartContentMessage = new MimeMessage();
        $bodyMessage = new MimeMessage();

        $multiPartContentMessage->setParts(array($text, $pdfPart));
        $multiPartContentMimePart = new MimePart($multiPartContentMessage->generateMessage());
        $multiPartContentMimePart->type = 'multipart/mixed;' . PHP_EOL . ' boundary="' .
        $multiPartContentMessage->getMime()->boundary() . '"';

        $bodyMessage->addPart($multiPartContentMimePart);

        // instance mail
        $mail = new Mail\Message();

        $mail->setBody($bodyMessage);
        $mail->setFrom('penningmeester@example.com', 'Penningmeester');
        $mail->setTo($invoice->getOwner()->getEmail());
        $mail->setSubject('Uw factuur');

        $mail->getHeaders()->get('content-type')->setType('multipart/mixed');
        $transport = $this->getServiceLocator()->get('MailTransport')->send($mail);

        $view = new ViewModel();
        $view->setTerminal(true);

        return $view;
    }

    public function deletefileAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('owner');
        }

        /* @var $invoice Application\Entity\Invoice */
        $invoice = $this->getEntityManager()->find('Application\Entity\Invoice', (int) $this->params()->fromRoute('id'));

        if (file_exists('/tmp/pdf/' . $invoice->getFilename())) {
            unlink('/tmp/pdf/' . $invoice->getFilename());
        }

        $view = new ViewModel();
        $view->setTerminal(true);

        return $view;
    }

    protected function addAction()
    {
        $owner = $this->getEntityManager()->find('Application\Entity\Owner', $this->params()->fromRoute('id'));
        $invoice = new Invoice();

        $form = $this->getServiceLocator()->get('FormElementManager')->get('InvoiceForm');
        $form->bind($invoice);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $invoice->setOwner($owner);

                $this->getEntityManager()->persist($invoice);
                $this->getEntityManager()->flush();

                // Redirect to owner view
                return $this->redirect()->toRoute('owner', array('action' => 'view', 'id' => $invoice->getOwner()->getId()));
            }
        } else {
            $form->get('year')->setValue(date('Y'));
            $form->get('filename')->setValue('factuur-' . str_replace(' ', '', $owner->getInitials() . $owner->getName()) . '-' . date('Y') . '.pdf');
        }

        return array('form' => $form, 'owner' => $owner);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('owner', array(
                'action' => 'add'
            ));
        }
        $owner = $this->getOwnerMapper()->getOwner($id);

        $form  = new OwnerForm();
        $form->bind($owner);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($owner->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getOwnerMapper()->saveOwner($form->getData());

                // Redirect to list of owners
                return $this->redirect()->toRoute('owner');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function viewAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('owner');
        }

        return $this->getFile($id);
    }

    public function downloadAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('owner');
        }

        return $this->getFile($id, 'attachment');
    }

    protected function getFile($id, $disposition = 'inline')
    {
        /* @var $invoice Application\Entity\Invoice */
        $invoice = $this->getEntityManager()->find('Application\Entity\Invoice', (int) $this->params()->fromRoute('id'));

        if (!file_exists('/tmp/pdf/' . $invoice->getFilename())) {
            $this->createPdf();
        }

        $pdf = \ZendPdf\PdfDocument::load('/tmp/pdf/' . $invoice->getFilename(), 1);
        $fileContents = $pdf->render();

        $response = $this->getResponse();
        $response->setContent($fileContents);

        $headers = $response->getHeaders();
        $headers->clearHeaders()
            ->addHeaderLine('Content-Type', 'application/pdf')
            ->addHeaderLine('Content-Disposition', $disposition . '; filename="' . $invoice->getFilename() . '"')
            ->addHeaderLine('Content-Length', strlen($fileContents));

        return $this->response;
    }

    public function createPdf()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('owner');
        }

        /* @var $invoice Application\Entity\Invoice */
        $invoice = $this->getEntityManager()->find('Application\Entity\Invoice', (int) $this->params()->fromRoute('id'));

        $invoiceDocument = $this->getServiceLocator()->get('InvoiceDocument');

        $pdf = $invoiceDocument->create($invoice);
        $fileName = $invoice->getFilename();

        $pdf->save('/tmp/pdf/' . $fileName);

        return true;
    }

    protected function getSalutation($owner)
    {
        $salutationMale = array('nl' => 'heer', 'en' => 'Mr.', 'de' => 'Herrn');
        $salutationFemale = array('nl' => 'mevrouw', 'en' => 'Mrs.', 'de' => 'Frau');
        $salutationUnknown = array('nl' => 'heer/mevrouw', 'en' => 'Mr./Mrs.', 'de' => 'Herrn/Frau');

        switch ($owner->getGender()) {
            case 'M':
                return $salutationMale[strtolower($owner->getLanguage())];
                break;

            case 'F':
                return $salutationFemale[strtolower($owner->getLanguage())];
                break;

            default:
                return $salutationUnknown[strtolower($owner->getLanguage())];
                break;
        }
    }
}
