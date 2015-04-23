<?php

namespace LHelene\OrderBundle\Command;

use LHelene\OrderBundle\Exception\PromotionNotFoundException;
use LHelene\OrderBundle\Loader\ProductLoader;
use LHelene\OrderBundle\Promotion\PromotionBase;
use LHelene\OrderBundle\Service\PromotionService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateTotalCommand extends ContainerAwareCommand
{
    const ARG_FILENAME       = 'file';
    const ARG_PROMOTION_NAME = 'promotion';

    /**
     * @var ProductLoader
     */
    protected $productLoader;

    /**
     * @var PromotionService
     */
    protected $promotionService;

    /**
     * @var PromotionBase
     */
    protected $promotion;

    /**
     * @var string
     */
    protected $path;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('lhelene:order:update-total')
            ->setDescription('Update the total of an order from an XML file')
            ->setHelp(<<<EOT
Usage:
 lhelene:order:update-total file [promotion]

Arguments:
  file              path to the file that contains products
  promotion         code that identifies the promotion

Help:
 Load a XML file to update the total base on a promotion
EOT
            )
            ->addArgument(self::ARG_FILENAME, InputArgument::REQUIRED, 'Filename')
            ->addArgument(self::ARG_PROMOTION_NAME, InputArgument::OPTIONAL, 'Promotion code')
            ;
    }

    /**
     *
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $container              = $this->getContainer();
        $this->productLoader    = $container->get('lhelene.order.loader.product');
        $this->promotionService = $container->get('lhelene.order.service.promotion');
        $code                   = $input->getArgument(self::ARG_PROMOTION_NAME);
        $this->path             = $container->getParameter('kernel.root_dir') . '/../';

        try {
            $this->promotion        = null === $code ? $this->promotionService->getFirstPromotion() : $this->promotionService->getPromotion($code);
        } catch (PromotionNotFoundException $e) {
        }

        if (null === $this->promotion) {
            $output->writeln("Invalid promotion code.");
            $output->writeln("Here the list of valid promotion codes:");
            foreach ($this->promotionService->getPromotionList() as $promotion) {
                $output->writeln(sprintf("  Promotion code:\"%s\" execute that: %s", $promotion->getName(), $promotion->getDescription()));
            }

            throw new PromotionNotFoundException($code);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->productLoader->load($this->path . $input->getArgument(self::ARG_FILENAME))) {
            $total = $this->promotion->calculate();
            $this->productLoader->updateTotal($total);
            $output->writeln(sprintf("New total is %f", $total));

        }
    }
}
