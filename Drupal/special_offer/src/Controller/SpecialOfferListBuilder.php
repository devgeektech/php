<?php

namespace Drupal\special_offer\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Provides a list controller for special_offer entity.
 *
 * @ingroup special_offer
 */
class SpecialOfferListBuilder extends EntityListBuilder
{
    /**
     * {@inheritdoc}
     *
     * We override ::render() so that we can add our own content above the table.
     * parent::render() is where EntityListBuilder creates the table using our
     * buildHeader() and buildRow() implementations.
     */
    public function render()
    {
        $build['description'] = [
            '#markup' => $this->t("This is where you can add and edit special offers"),
        ];

        $build += parent::render();
        return $build;
    }

    /**
     * {@inheritdoc}
     *
     * Building the header and content lines for the contact list.
     *
     * Calling the parent::buildHeader() adds a column for the possible actions
     * and inserts the 'edit' and 'delete' links as defined for the entity type.
     */
    public function buildHeader()
    {
        $header['offer_name'] = $this->t('Offer Name');
        $header['title'] = $this->t('Title');
        return $header + parent::buildHeader();
    }

    /**
     * {@inheritdoc}
     */
    public function buildRow(EntityInterface $entity)
    {
        /* @var $entity \Drupal\special_offer\Entity\SpecialOffer */
        
        $row['offer_name'] = $entity->offer_name->value;
        $row['title'] = $entity->title->value;
        return $row + parent::buildRow($entity);
    }
}