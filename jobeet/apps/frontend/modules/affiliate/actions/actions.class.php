<?php

/**
 * affiliate actions.
 *
 * @package    jobeet
 * @subpackage affiliate
 * @author     Your name here
 */
class affiliateActions extends sfActions
{
    public function executeNew(sfWebRequest $request)
    {
        $this->form = new JobeetAffiliateForm();
    }

    public function executeCreate(sfWebRequest $request)
    {
        $this->forward404Unless($request->isMethod('post'));

        $this->form = new JobeetAffiliateForm();

        $this->processForm($request, $this->form);

        $this->setTemplate('new');
    }

    protected function processForm(sfWebRequest $request, sfForm $form)
    {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $jobeet_affiliate = $form->save();

            $this->redirect('affiliate/edit?id=' . $jobeet_affiliate->getId());
        }
    }

    public function executeWait()
    {
    }
}
