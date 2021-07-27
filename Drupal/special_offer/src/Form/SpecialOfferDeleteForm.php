<?php

namespace Drupal\special_offer\Form;

use Drupal\Core\Entity\ContentEntityConfirmFormBase;
use Drupal\Core\Entity\EntityMalformedException;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\GeneratedUrl;

/**
 * Provides a form for deleting a special_offer entity.
 *
 * @ingroup special_offer
 */
class SpecialOfferDeleteForm extends ContentEntityConfirmFormBase
{
    /**
     * Returns the question to ask the user.
     *
     * @return string The form question. The page title will be set to this value.
     */
    public function getQuestion()
    {
        return "Are you sure you want to delete this special offer?";
    }

    /**
     * Returns the route to go to if the user cancels the action.
     *
     * @return GeneratedUrl A URL object.
     */
    public function getCancelUrl()
    {
        return \Drupal::urlGenerator()->generateFromRoute('entity.special_offer.collection');
    }

    /**
     * What to do when the delete form is submitted
     *
     * @param  array                    $form
     * @param  FormStateInterface       $form_state
     * @throws EntityMalformedException
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        try {
            $this->entity->delete();
        } catch (EntityStorageException $e) {
            $messenger = \Drupal::messenger();

            $messenger->addError(
                $this->t(
                    'The special offer %feed failed to be deleted. Error: %error',
                    ['%feed' => $this->entity->toLink()->toString(), '%error' => $e->getMessage()]
                )
            );
        }

        parent::submitForm($form, $form_state);
    }
}