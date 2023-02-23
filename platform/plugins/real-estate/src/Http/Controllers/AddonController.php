<?php
namespace Botble\RealEstate\Http\Controllers;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\RealEstate\Http\Requests\AddonRequest;
use Botble\RealEstate\Repositories\Interfaces\AddonInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Exception;
use Botble\RealEstate\Tables\AddonTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\RealEstate\Forms\AddonForm;
use Botble\Base\Forms\FormBuilder;
use Illuminate\View\View;
use Throwable; 
class AddonController extends BaseController
{
    /**
     * @var AddonInterface
     */
    protected $addonRepository;

    /**
     * TypeController constructor.
     * @param AddonInterface $addonRepository
     */
    public function __construct(AddonInterface $addonRepository)
    {
        $this->addonRepository = $addonRepository;
    }

    /**
     * @param AddonTable $dataTable
     * @return Factory|View
     * @throws Throwable
     */
    public function index(AddonTable $table)
    {
        page_title()->setTitle(trans('plugins/real-estate::addon.name'));
        return $table->renderTable();
    } 


    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/real-estate::addon.create'));
        return $formBuilder->create(AddonForm::class)->renderForm();
    }

    /**
     * Insert new Type into database
     *
     * @param AddonRequest $request
     * @return BaseHttpResponse
     */
    public function store(AddonRequest $request, BaseHttpResponse $response)
    {
        $addon = $this->addonRepository->getModel();

        $addon->fill($request->input());

        $addon->slug = $this->addonRepository->createSlug($request->get('slug'), 0);

        $addon = $this->addonRepository->createOrUpdate($addon);

        event(new CreatedContentEvent(PROPERTY_TYPE_MODULE_SCREEN_NAME, $request, $addon));

        return $response
            ->setPreviousUrl(route('addon.index'))
            ->setNextUrl(route('addon.edit', $addon->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * Show edit form
     *
     * @param $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $addon = $this->addonRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $addon));

        page_title()->setTitle(trans('plugins/real-estate::addon.edit') . ' "' . $addon->name . '"');

        return $formBuilder->create(AddonForm::class, ['model' => $addon])->renderForm();
    }

    /**
     * @param $id
     * @param AddonRequest $request
     * @return BaseHttpResponse
     */
    public function update($id, AddonRequest $request, BaseHttpResponse $response)
    {
        $addon = $this->addonRepository->findOrFail($id);

        $addon->fill($request->input());

        $this->addonRepository->createOrUpdate($addon);

        event(new UpdatedContentEvent(PROPERTY_TYPE_MODULE_SCREEN_NAME, $request, $addon));

        return $response
            ->setPreviousUrl(route('addon.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param $id
     * @param Request $request
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $addon = $this->addonRepository->findOrFail($id);

            $this->addonRepository->delete($addon);

            event(new DeletedContentEvent(PROPERTY_TYPE_MODULE_SCREEN_NAME, $request, $addon));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.cannot_delete'));
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $addon = $this->addonRepository->findOrFail($id);
            $this->addonRepository->delete($addon);

            event(new DeletedContentEvent(PROPERTY_TYPE_MODULE_SCREEN_NAME, $request, $addon));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
