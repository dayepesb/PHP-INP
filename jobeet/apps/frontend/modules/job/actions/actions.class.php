<?php

/**
 * job actions.
 *
 * @package    jobeet
 * @subpackage job
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class jobActions extends sfActions
{
    public function executeJobDelete(sfWebRequest $reques){
        $input = file_get_contents('php://input');
        $decode = json_decode($input);
        $id = $decode->id;
        $this->listJobs = JobeetJobPeer::getActiveJobs();
        //return $this->renderText(json_encode($this->listJobs[0]->getId()));
        foreach ($this->listJobs as $job) {
            if ($id == $job->getId()) {
                $job->delete();
                return $this->renderText('delete');
            }
        }
        return $this->renderText('no delete');
    }
    public function executeNewPost(sfWebRequest $reques)
    {
        //POST :  post save job
        $input = file_get_contents('php://input');
        $decode = json_decode($input);
        $job = new JobeetJob;
        $job->setCategoryId($decode->category);
        $job->setType($decode->type);
        $job->setCompany($decode->company);
        $job->setUrl($decode->url);
        $job->setPosition($decode->position);
        $job->setLocation($decode->location);
        $job->setDescription($decode->description);
        $job->setHowToApply($decode->howtoapply);
        $job->setIsPublic($decode->ispublic);
        $job->setIsActivated(true);
        $job->save();
        return $this->renderText(json_encode($job));
    }

    public function executeCategoryJob(sfWebRequest $reques)
    {
        // GET : get category for id
        // PAMETERS : { id: {{ id cateogry }} }
        $id = $reques->getParameter('id');
        $this->categories = JobeetCategoryPeer::getWithJobs();
        foreach ($this->categories as $category) {
            if ($id == $category->getId())
                return $this->renderText(json_encode($category));
        }
    }

    public function executeJobView(sfWebRequest $reques)
    {
        // GET : get job
        // PAMETERS : { id: {{ id job }} }
        $id = $reques->getParameter('id');
        $this->listJobs = JobeetJobPeer::getActiveJobs();
        //return $this->renderText(json_encode($this->listJobs[0]->getId()));
        foreach ($this->listJobs as $job) {
            if ($id == $job->getId()) {
                return $this->renderText(json_encode($job));
            }
        }
    }

    public function executeJsonCategory(sfWebRequest $reques)
    {
        // GET : get category for slug
        // PAMETERS : { slug: {{ slug category }} }

        if ($reques->hasParameter('slug')) {
            $category = JobeetCategoryPeer::getForSlug($reques->getParameter('slug'));
            $list = $category->getActiveJobs($category->countActiveJobs());
            $jobs = array();
            $jobs [] = $category;
            $jobs [] = $list;
        } else {
            $jobs = array();
            $this->categories = JobeetCategoryPeer::getWithJobs();
            foreach ($this->categories as $category) {
                $arrayCategory = array();
                $arrayCategory [] = $category;
                $arrayCategory [] = $category->getActiveJobs();
                $jobs[] = $arrayCategory;
            }
        }
        return $this->renderText(json_encode($jobs));
    }

    public function executeJson(sfWebRequest $reques)
    {
        // GET : get all Jobs
        $this->categories = JobeetJobPeer::getActiveJobs();
        return $this->renderText(json_encode($this->categories));
    }

    public function executeIndex(sfWebRequest $request)
    {
        if (!$request->getParameter('sf_culture')) {
            if ($this->getUser()->isFirstRequest()) {
                $culture = $request->getPreferredCulture(array('en', 'fr'));
                $this->getUser()->setCulture($culture);
                $this->getUser()->isFirstRequest(false);
            } else {
                $culture = $this->getUser()->getCulture();
            }

            $this->redirect('@localized_homepage');
        }
        $this->categories = JobeetCategoryPeer::getWithJobs();
    }

    public function executeSearch(sfWebRequest $request)
    {
        if (!$query = $request->getParameter('query')) {
            return $this->forward('job', 'index');
        }

        $this->jobs = JobeetJobPeer::getForLuceneQuery($query);

        if ($request->isXmlHttpRequest()) {
            if ('*' == $query || !$this->jobs) {
                return $this->renderText('No results.');
            } else {
                return $this->renderPartial('job/list', array('jobs' => $this->jobs));
            }
        }
    }

    public function executeShow(sfWebRequest $request)
    {
        $this->job = $this->getRoute()->getObject();

        $this->getUser()->addJobToHistory($this->job);
    }

    public function executeNew(sfWebRequest $request)
    {
        $job = new JobeetJob();
        $job->setType('full-time');

        $this->form = new JobeetJobForm($job);
    }

    public function executeCreate(sfWebRequest $request)
    {
        $this->form = new JobeetJobForm();
        $this->processForm($request, $this->form);
        $this->setTemplate('new');
    }

    public function executeEdit(sfWebRequest $request)
    {
        $job = $this->getRoute()->getObject();
        $this->forward404If($job->getIsActivated());

        $this->form = new JobeetJobForm($job);
    }

    public function executeUpdate(sfWebRequest $request)
    {
        $this->form = new JobeetJobForm($this->getRoute()->getObject());
        $this->processForm($request, $this->form);
        $this->setTemplate('edit');
    }

    public function executePublish(sfWebRequest $request)
    {
        $request->checkCSRFProtection();

        $job = $this->getRoute()->getObject();
        $job->publish();

        $this->getUser()->setFlash('notice', sprintf('Your job is now online for %s days.', sfConfig::get('app_active_days')));

        $this->redirect($this->generateUrl('job_show_user', $job));
    }

    public function executeExtend(sfWebRequest $request)
    {
        $request->checkCSRFProtection();

        $job = $this->getRoute()->getObject();
        $this->forward404Unless($job->extend());

        $this->getUser()->setFlash('notice', sprintf('Your job validity has been extend until %s.', $job->getExpiresAt('m/d/Y')));

        $this->redirect($this->generateUrl('job_show_user', $job));
    }

    public function executeDelete(sfWebRequest $request)
    {
        $request->checkCSRFProtection();

        $job = JobeetJobPeer::retrieveByPk($request->getParameter('id'));
        $this->forward404Unless($job, sprintf('Object jobeet_job does not exist (%s).', $request->getParameter('id')));
        $job->delete();

        $this->redirect('job/index');
    }

    protected function processForm(sfWebRequest $request, sfForm $form)
    {
        $form->bind(
            $request->getParameter($form->getName()),
            $request->getFiles($form->getName())
        );

        if ($form->isValid()) {
            $job = $form->save();

            $this->redirect($this->generateUrl('job_show', $job));
        }
    }


}
