<?php

namespace Drupal\special_offer\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the special_offer entity edit forms.
 *
 * @ingroup special_offer
 */
class SpecialOfferForm extends ContentEntityForm
{
    /**
     * {@inheritdoc}
     */
    public function save(array $form, FormStateInterface $form_state)
    {
        $status = parent::save($form, $form_state);

        $entity = $this->entity;

        $messenger = \Drupal::messenger();

        if ($status == SAVED_UPDATED) {
            $messenger->addMessage(
                $this->t('The special offer %feed has been updated.', ['%feed' => $entity->toLink()->toString()])
            );
        } else {
            $messenger->addMessage(
                $this->t('The special offer %feed has been added.', ['%feed' => $entity->toLink()->toString()])
            );
        }

        $form_state->setRedirectUrl($this->entity->toUrl('collection'));
        return $status;
    }
}