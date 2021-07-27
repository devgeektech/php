<?php

namespace Drupal\special_offer\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\user\UserInterface;

/**
 * Defines the special offer entity class.
 *
 * @ContentEntityType(
 *   id = "special_offer",
 *   label = @Translation("Special Offer"),
 *   base_table = "special_offer",
 *   fieldable = true,
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "title",
 *     "copy" = "copy",
 *     "image" = "image",
 *     "term_condition_lable" = "term_condition_lable",
 *   },
 *   admin_permission = "administer special_offer entity",
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\special_offer\Controller\SpecialOfferListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "default" = "Drupal\special_offer\Form\SpecialOfferForm",
 *       "add" = "Drupal\special_offer\Form\SpecialOfferForm",
 *       "edit" = "Drupal\special_offer\Form\SpecialOfferForm",
 *       "delete" = "Drupal\special_offer\Form\SpecialOfferDeleteForm",
 *     },
 *     "access" = "Drupal\special_offer\SpecialOfferAccessControlHandler",
 *   },
 *   links = {
 *     "canonical" = "/admin/special_offer/{special_offer}",
 *     "add-page" = "/admin/special_offer/add",
 *     "add-form" = "/admin/special_offer/add",
 *     "edit-form" = "/admin/special_offer/{special_offer}/edit",
 *     "delete-form" = "/admin/special_offer/{special_offer}/delete",
 *     "collection" = "/admin/special_offer/list"
 *   }
 * )
 */
class SpecialOffer extends ContentEntityBase implements ContentEntityInterface
{
    use EntityChangedTrait; // Implements methods defined by EntityChangedInterface.

    /**
     * {@inheritdoc}
     *
     * When a new entity instance is added, set the user_id entity reference to
     * the current user as the creator of the instance.
     */
    public static function preCreate(EntityStorageInterface $storage_controller, array &$values)
    {
        parent::preCreate($storage_controller, $values);
        $values += array(
            'user_id' => \Drupal::currentUser()->id(),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedTime()
    {
        return $this->get('created')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function getOwner()
    {
        return $this->get('user_id')->entity;
    }

    /**
     * {@inheritdoc}
     */
    public function getOwnerId()
    {
        return $this->get('user_id')->target_id;
    }

    /**
     * {@inheritdoc}
     */
    public function setOwnerId($uid)
    {
        $this->set('user_id', $uid);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setOwner(UserInterface $account)
    {
        $this->set('user_id', $account->id());
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * Define the field properties here.
     *
     * Field name, type and size determine the table structure.
     *
     * In addition, we can define how the field and its content can be manipulated
     * in the GUI. The behaviour of the widgets used can be determined here.
     */
    public static function baseFieldDefinitions(EntityTypeInterface $entity_type)
    {
        $weight = 0;

        $fields['id'] = BaseFieldDefinition::create('integer')
            ->setLabel(t('ID'))
            ->setDescription(t('The ID of the special offer'))
            ->setReadOnly(true)
            ->setRequired(true);

        $fields['practice'] = BaseFieldDefinition::create('entity_reference')
            ->setLabel(t('Practice'))
            ->setDescription(t('The practice this special offer belongs to (if required)'))
            ->setRequired(false)
            ->setSettings([
                'target_type'      => 'node',
                'handler'          => 'default',
                'handler_settings' => [
                    'target_bundles' => [
                        'practice' => 'practice'
                    ]
                ]
            ])
            ->setDisplayOptions('view', [
                'label'  => 'hidden',
                'type'   => 'practice',
                'weight' => $weight
            ])
            ->setDisplayOptions('form', [
                'type'     => 'entity_reference_autocomplete',
                'weight'   => $weight,
            ]);

        $weight++;

        $fields['image'] = BaseFieldDefinition::create('image')
            ->setLabel(t('Image'))
            ->setDescription('The image that will appear at the top of each special offer')
            ->setRequired(true)
            ->setSettings([
                'file_extensions'    => 'png jpg jpeg',
                'alt_field_required' => 0,
                'uri_scheme'         => 's3',
            ])
            ->setDisplayOptions('view', [
                'label'   => 'hidden',
                'type'    => 'image',
                'weight'  => $weight,
            ])
            ->setDisplayOptions('form', [
                'type'    => 'image_image',
                'weight'  => $weight,
            ]);

        $weight++;

        $fields['title'] = BaseFieldDefinition::create('string')
            ->setLabel(t('Title'))
            ->setDescription(t('The title that will appear below the image'))
            ->setRequired(true)
            ->setSettings([
                'default_value'   => '',
                'max_length'      => 255,
                'text_processing' => 0,
            ])
            ->setDisplayOptions('view', [
                'label'  => 'hidden',
                'type'   => 'string',
                'weight' => $weight,
            ])
            ->setDisplayOptions('form', [
                'type'   => 'string_textfield',
                'weight' => $weight,
            ]);

        $weight++;

        $fields['offer_name'] = BaseFieldDefinition::create('string')
            ->setLabel(t('Offer Name'))
            ->setDescription(t('The title that will appear below the title'))
            ->setRequired(true)
            ->setSettings([
                'default_value'   => '',
                'max_length'      => 255,
                'text_processing' => 0,
            ])
            ->setDisplayOptions('view', [
                'label'  => 'hidden',
                'type'   => 'string',
                'weight' => $weight,
            ])
            ->setDisplayOptions('form', [
                'type'   => 'string_textfield',
                'weight' => $weight,
            ]);

        $weight++;

        $fields['end_date'] = BaseFieldDefinition::create('datetime')
            ->setLabel(t('End date'))
            ->setDescription(t("The end date of the offer test...."))
         
          ->setRequired(false)
          ->setDefaultValue(' ')
          ->setSettings([
                'datetime_type' => 'date'
            ])
            ->setDisplayOptions('view', [
                'label'  => 'hidden',
                'type'   => 'date',
                'weight' => $weight,
            ])
            ->setDisplayOptions('form', [
                'type'   => 'date',
                'weight' => $weight,
            ]);

        $weight++;


        $fields['copy'] = BaseFieldDefinition::create('string_long')
            ->setDescription(t('The copy that will appear below the image / title / end date'))
            ->setLabel(t('Copy'))
            ->setRequired(true)
            ->setSettings([
                'rows'         => 9,
                'placeholder'  => '',
            ])
            ->setDisplayOptions('view', [
                'label'  => 'hidden',
                'type'   => 'string',
                'weight' => $weight,
            ])
            ->setDisplayOptions('form', [
                'type'   => 'string_textarea',
                'weight' => $weight,
            ]);

        $weight++;

        $fields['featured'] = BaseFieldDefinition::create('boolean')
            ->setLabel(t("Featured"))
            ->setDescription("Whether the special offer should be in the featured list")
            ->setRequired(false)
            ->setSettings([
                'on_label'  => '1',
                'off_label' => '0'
            ])
            ->setDisplayOptions('view', [
                'label' => 'hidden',
                'weight' => $weight
            ])
            ->setDisplayOptions('form', [
                'type'   => 'boolean_checkbox',
                'format' => 'boolean',
                'weight' => $weight
            ]);
 $weight++;
        $fields['redirect_link'] = BaseFieldDefinition::create('string_long')
            ->setDescription(t('The copy that will appear below the image / title / end date'))
            ->setLabel(t('Redirect Link'))
            ->setRequired(true)
            ->setSettings([
                'rows'         => 9,
                'placeholder'  => '',
            ])
            ->setDisplayOptions('view', [
                'label'  => 'hidden',
                'type'   => 'string',
                'weight' => $weight,
            ])
            ->setDisplayOptions('form', [
                'type'   => 'string_textarea',
                'weight' => $weight,
            ]);

        $weight++;
       
           $fields['select_color'] = BaseFieldDefinition::create('string')
            ->setLabel(t('Add Color'))
            ->setDescription(t('The Color will appear on the background of text above the image'))
            ->setRequired(true)
            ->setSettings([
                'default_value'   => 'FFE5B4',
                'max_length'      => 255,
                'text_processing' => 0,
            ])
            ->setDisplayOptions('view', [
                'label'  => 'hidden',
                'type'   => 'string',
                'weight' => $weight,
            ])
            ->setDisplayOptions('form', [
                'type'   => 'string_textfield',
                'weight' => $weight,
            ]);

        $weight++;

           $fields['font_size'] = BaseFieldDefinition::create('string')
            ->setLabel(t('Add Font Size'))
            ->setDescription(t('The size  will be applied on the text appear above the image'))
            ->setRequired(true)
            ->setSettings([
                'default_value'   => '12',
                'max_length'      => 255,
                'text_processing' => 0,
            ])
            ->setDisplayOptions('view', [
                'label'  => 'hidden',
                'type'   => 'string',
                'weight' => $weight,
            ])
            ->setDisplayOptions('form', [
                'type'   => 'string_textfield',
                'weight' => $weight,
            ]);

        $weight++;

        $fields['term_condition_lable'] = BaseFieldDefinition::create('string')
        ->setLabel(t('Terms and condition link'))
        ->setRequired(true)
        ->setSettings([
            'default_value'   => '',
            'max_length'      => 255,
            'text_processing' => 0,
        ])
        ->setDisplayOptions('view', [
            'label'  => 'hidden',
            'type'   => 'string',
            'weight' => $weight,
        ])
        ->setDisplayOptions('form', [
            'type'   => 'string_textfield',
            'weight' => $weight,
        ]);

        $weight++; 

      
    return $fields;
    }


}