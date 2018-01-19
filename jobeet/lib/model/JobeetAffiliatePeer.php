<?php

class JobeetAffiliatePeer extends BaseJobeetAffiliatePeer
{
    static public function getForToken(array $parameters)
    {
        $affiliate = JobeetAffiliatePeer::getByToken($parameters['token']);
        if (!$affiliate || !$affiliate->getIsActive()) {
            throw new sfError404Exception(sprintf('Affiliate with token "%s" does not exist or is not activated.', $parameters['token']));
        }

        return $affiliate->getActiveJobs();
    }

    static public function getByToken($token)
    {
        $criteria = new Criteria();
        $criteria->add(self::TOKEN, $token);

        return self::doSelectOne($criteria);
    }

    public function getActiveJobs()
    {
        $cas = $this->getJobeetCategoryAffiliates();
        $categories = array();
        foreach ($cas as $ca) {
            $categories[] = $ca->getCategoryId();
        }

        $criteria = new Criteria();
        $criteria->add(JobeetJobPeer::CATEGORY_ID, $categories, Criteria::IN);
        JobeetJobPeer::addActiveJobsCriteria($criteria);

        return JobeetJobPeer::doSelect($criteria);
    }

    static public function countToBeActivated()
    {
        $criteria = new Criteria();
        $criteria->add(self::IS_ACTIVE, 0);

        return self::doCount($criteria);
    }
}
