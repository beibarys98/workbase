<?php

namespace frontend\controllers;

use common\models\Document;
use common\models\Work;
use common\models\WorkSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * WorkController implements the CRUD actions for Work model.
 */
class WorkController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Work models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new WorkSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Work model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $documents = new ActiveDataProvider([
            'query' => Document::find()->where(['work_id' => $id]),
        ]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'documents' => $documents
        ]);
    }

    public function actionDownload($id)
    {
        $model = Document::findOne($id);
        if ($model !== null) {
            $filePath = $model->path;

            if (strpos($filePath, '/app') !== 0) {
                $filePath = '/app/frontend/web/uploads' . $filePath;
            }

            return Yii::$app->response->sendFile($filePath, $model->file_name, ['inline' => true]);
        }
        throw new NotFoundHttpException('The requested file does not exist.');
    }

    /**
     * Creates a new Work model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Work();
        $document = new Document();
        $document2 = new Document();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())
                && $document->load($this->request->post())
                && $document2->load($this->request->post())) {

                $doc = $uploadedFile = UploadedFile::getInstance($document, 'doc');
                $pdf = $uploadedFile2 = UploadedFile::getInstance($document2, 'pdf');

                $model->is_visible = true;
                $model->save(false);

                $filePath = Yii::getAlias('@frontend/web/uploads/').$doc->name;
                $filePath2 = Yii::getAlias('@frontend/web/uploads/').$pdf->name;

                if($doc && $uploadedFile->saveAs($filePath)){
                    $document->work_id = $model->id;
                    $document->file_type_id = $model->file_type_id;
                    $document->file_name = $doc->name;
                    $document->path = $filePath;
                    $document->save(false);
                }

                if($pdf && $uploadedFile2->saveAs($filePath2)){
                    $document2->work_id = $model->id;
                    $document2->file_type_id = $model->file_type_id;
                    $document2->file_name = $pdf->name;
                    $document2->path = $filePath2;
                    $document2->save(false);
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'document' => $document,
            'document2' => $document2,
        ]);
    }

    /**
     * Updates an existing Work model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $document = Document::find()
            ->where(['work_id' => $id])
            ->andWhere(['or', ['like', 'path', '%.doc', false],
                ['like', 'path', '%.docx', false]])
            ->one();
        if($document === null){
            $document = new Document();
        }

        $document2 = Document::find()
            ->where(['work_id' => $id])
            ->andWhere(['like', 'path', '%.pdf', false])
            ->one();
        if($document2 === null){
            $document2 = new Document();
        }

        $documents = new ActiveDataProvider([
            'query' => Document::find()->where(['work_id' => $id]),
        ]);

        if ($this->request->isPost
            && $model->load($this->request->post())
            && $document->load($this->request->post())
            && $document2->load($this->request->post())) {

            $doc = $uploadedFile = UploadedFile::getInstance($document, 'doc');
            $pdf = $uploadedFile2 = UploadedFile::getInstance($document2, 'pdf');

            $model->save(false);

            if($doc){
                $filePath = Yii::getAlias('@frontend/web/uploads/').$doc->name;
            }

            if($pdf){
                $filePath2 = Yii::getAlias('@frontend/web/uploads/').$pdf->name;
            }

            if($doc && $uploadedFile->saveAs($filePath)){
                $document->work_id = $model->id;
                $document->file_type_id = $model->file_type_id;
                $document->file_name = $doc->name;
                $document->path = $filePath;
                $document->save(false);
            }

            if($pdf && $uploadedFile2->saveAs($filePath2)){
                $document2->work_id = $model->id;
                $document2->file_type_id = $model->file_type_id;
                $document2->file_name = $pdf->name;
                $document2->path = $filePath2;
                $document2->save(false);
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'document' => $document,
            'document2' => $document2,
            'documents' => $documents
        ]);
    }

    /**
     * Deletes an existing Work model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $work = Work::findOne(['id' => $id]);
        $work->is_visible = false;
        $work->save(false);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Work model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Work the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Work::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
