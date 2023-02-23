<?php

namespace Botble\RealEstate\Forms;

use Assets;
use Botble\RealEstate\Http\Requests\AccountPropertyRequest;

use Botble\Blog\Http\Requests\PostRequest;
use Botble\RealEstate\Models\Property;
use Botble\RealEstate\Forms\Fields\CustomEditorField;
use Botble\RealEstate\Forms\Fields\MultipleUploadField;
use Botble\RealEstate\Forms\Fields\CustomImageField;

use RealEstateHelper;
use Botble\Blog\Forms\PostForm; 
use Botble\Blog\Models\Post;

class AccountBlogForm extends PostForm
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        parent::buildForm();

        Assets::addScriptsDirectly('vendor/core/core/base/libraries/tinymce/tinymce.min.js');

        // Assets::addStylesDirectly('vendor/core/plugins/real-estate/css/account-admin.css')
        //     ->addScriptsDirectly(['/vendor/core/plugins/real-estate/js/account-admin.js']);

        if (!$this->formHelper->hasCustomField('customEditor')) {
            $this->formHelper->addCustomField('customEditor', CustomEditorField::class);
        }

        if (!$this->formHelper->hasCustomField('multipleUpload')) {
            $this->formHelper->addCustomField('multipleUpload', MultipleUploadField::class);
        }
        if (!$this->formHelper->hasCustomField('customImage')) {
            $this->formHelper->addCustomField('customImage', CustomImageField::class);
        }

        $this
            ->setupModel(new Post)
            ->setFormOption('template', 'plugins/real-estate::account.forms.base')
            ->setFormOption('enctype', 'multipart/form-data')
            ->setValidatorClass(PostRequest::class)
            ->setActionButtons(view('plugins/real-estate::account.forms.actions')->render())
            
            // ->remove('status')
            ->remove('is_featured')
            
            // ->remove('image')  
            ->addAfter('description', 'content', 'customEditor', [
                'label'      => trans('core/base::forms.content'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'rows' => 4,
                ],
            ])
            ->addAfter('content', 'image', 'file', [
                'label'      => trans('core/base::forms.image'),
                'label_attr' => ['class' => 'control-label'],
            ])
            // ->addAfter('content', 'image', 'image', [
            //     'label'      => trans('core/base::forms.image'),
            //     'label_attr' => ['class' => 'control-label'],
            //     'value'      => $this->getModel()->image,
            // ])

            // ->addAfter('content', 'image', 'file', [
            //     'label'      => trans('core/base::forms.image'),
            //     'label_attr' => ['class' => 'control-label'], 
            // ])
            // ->addAfter('content', 'images', 'file', [
            //     'label'      => trans('plugins/real-estate::property.form.images'),
            //     'label_attr' => ['class' => 'control-label'],
            // ])
            // ->setBreakFieldPoint('avatar_image')

            // Available types are: text, email, url, tel, search, password, hidden, number, date, file,
            //  image, color, datetime-local, month, range, time, week, select, textarea, button,
            //   buttongroup, submit, reset, radio, checkbox, choice, form, entity, collection, 
            //   repeated, static, categoryMulti, customSelect, editor, onOff, customRadio, 
            //   mediaImage, mediaImages, mediaFile, customColor, time, date, autocomplete, html, 
            //   repeater, permalink, tags, customEditor, multipleUpload
            
            // ->addAfter('image','categories[]', 'categoryMulti', [
            //     'label'      => trans('plugins/blog::posts.form.categories'),
            //     'label_attr' => ['class' => 'control-label required'],
            //     'choices'    => get_categories_with_children(),
            //     'value'      => old('categories', $selectedCategories),
            // ])
            ->addAfter('content','tag', 'tags', [
                'label'      => trans('plugins/blog::posts.form.tags'),
                'label_attr' => ['class' => 'control-label'],
                'value'      => $this->tags,
                'attr'       => [
                    'placeholder' => trans('plugins/blog::base.write_some_tags'),
                    'data-url'    => route('public.account.blogs.getAllTags'),
                ],
            ])
            ->setBreakFieldPoint('status');
            
            ;
    }
}
