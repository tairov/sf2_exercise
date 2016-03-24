<?php
namespace Yoda\EventBundle\Controller;



use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Yoda\EventBundle\Reporting\EventReportManager;


class ReportController extends Controller
{

    /**
     * @Route("/events/report/recentlyUpdated.csv")
     */
    public function updatedEventsAction()
    {
        $reportManager = $this->container->get('event_report_manager');
        $content = $reportManager->getRecentlyUpdatedReport();
        return new Response($content);
    }
}