<?php
namespace Volleyball\Bundle\UtilityBundle\Command;

class DashboardCommand extends \Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand
{
    /**
     * Configure
     */
    protected function configure()
    {
        $this
            ->setName('vb:dashboard:collect')
            ->setDescription('Generate widget data');
    }
    
    /**
     * Execute
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    ) {
        $widgetManager = $this->getContainer()->get('volleyball.dashboard.widget_manager');
        
        $widgets = $widgetManager->getWidgets();
        
        foreach ($widgets as $widget) {
            $widget->getCommand()->execute($input, $output);
        }
    }
}
