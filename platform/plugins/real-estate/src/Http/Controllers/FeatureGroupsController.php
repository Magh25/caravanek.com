<?php
namespace Botble\RealEstate\Http\Controllers;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\RealEstate\Http\Requests\FeatureGroupsRequest;
use Botble\RealEstate\Repositories\Interfaces\FeatureGroupsInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Exception;
use Botble\RealEstate\Tables\FeatureGroupsTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\RealEstate\Forms\FeatureGroupsForm;
use Botble\Base\Forms\FormBuilder;
use Illuminate\View\View;
use Throwable;

class FeatureGroupsController extends BaseController
{
    /**
     * @var FeatureGroupsInterface
     */
    protected $featureGroupsRepository;

    /**
     * TypeController constructor.
     * @param FeatureGroupsInterface $featureGroupsRepository
     */
    public function __construct(FeatureGroupsInterface $featureGroupsRepository)
    {
        $this->featureGroupsRepository = $featureGroupsRepository;
    }

    /**
     * @param FeatureGroupsTable $dataTable
     * @return Factory|View
     * @throws Throwable
     */
    public function index(FeatureGroupsTable $table)
    {
        page_title()->setTitle(trans('plugins/real-estate::feature_groups.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/real-estate::feature_groups.create'));
        return $formBuilder->create(FeatureGroupsForm::class)->renderForm();
    }

    /**
     * Insert new Type into database
     *
     * @param FeatureGroupsRequest $request
     * @return BaseHttpResponse
     */
    public function store(FeatureGroupsRequest $request, BaseHttpResponse $response)
    {
        $featureGroup = $this->featureGroupsRepository->getModel();

        $featureGroup->fill($request->input());

        $featureGroup->slug = $this->featureGroupsRepository->createSlug($request->get('slug'), 0);

        $featureGroup = $this->featureGroupsRepository->createOrUpdate($featureGroup);

        event(new CreatedContentEvent(PROPERTY_TYPE_MODULE_SCREEN_NAME, $request, $featureGroup));

        return $response
            ->setPreviousUrl(route('feature_groups.index'))
            ->setNextUrl(route('feature_groups.edit', $featureGroup->id))
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
        $featureGroup = $this->featureGroupsRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $featureGroup));

        page_title()->setTitle(trans('plugins/real-estate::feature_groups.edit') . ' "' . $featureGroup->name . '"');

        return $formBuilder->create(FeatureGroupsForm::class, ['model' => $featureGroup])->renderForm();
    }

    /**
     * @param $id
     * @param FeatureGroupsRequest $request
     * @return BaseHttpResponse
     */
    public function update($id, FeatureGroupsRequest $request, BaseHttpResponse $response)
    {
        $featureGroup = $this->featureGroupsRepository->findOrFail($id);

        $featureGroup->fill($request->input());

        $this->featureGroupsRepository->createOrUpdate($featureGroup);

        event(new UpdatedContentEvent(PROPERTY_TYPE_MODULE_SCREEN_NAME, $request, $featureGroup));

        return $response
            ->setPreviousUrl(route('feature_groups.index'))
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
            $featureGroup = $this->featureGroupsRepository->findOrFail($id);

            $this->featureGroupsRepository->delete($featureGroup);

            event(new DeletedContentEvent(PROPERTY_TYPE_MODULE_SCREEN_NAME, $request, $featureGroup));

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
            $featureGroup = $this->featureGroupsRepository->findOrFail($id);
            $this->featureGroupsRepository->delete($featureGroup);

            event(new DeletedContentEvent(PROPERTY_TYPE_MODULE_SCREEN_NAME, $request, $featureGroup));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
