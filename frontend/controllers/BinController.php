<?php

namespace frontend\controllers;

use Exception;
use Yii;
use common\models\Work;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * WorkController implements the CRUD actions for Work model.
 */
class BinController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'only' => ['create', 'update', 'delete'],
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
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
    public function actionIndex($year = '')
    {
	    $dataProvider = new ActiveDataProvider([
            'query' => Work::find()->andWhere(['is_visible' => 0]),
        ]);


        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'year' => $year
        ]);
    }

    public function actionHide($year = ''){
        $work = Work::find()->andWhere(['year' => $year])->all();
        foreach ($work as $w) {
            $w->is_visible = false;
            $w->save(false);
        }

        return $this->redirect(['index']);
    }

    public function actionShow($year = ''){
        $work = Work::find()->andWhere(['year' => $year])->all();
        foreach ($work as $w) {
            $w->is_visible = true;
            $w->save(false);
        }

        return $this->redirect(['index']);
    }

    /**
     * Displays a single Work model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $dataProvider = new ActiveDataProvider([
            'query' => $model->getDocuments()
        ]);
        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,

        ]);
    }

    /**
     * Creates a new Work model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($doc_type_id = null)
    {
        if ($doc_type_id){
            $docType = DocumentType::findOne($doc_type_id);
        }
        if (!$doc_type_id || !$docType){
            return $this->render('select_doc_type');
        }

        $model = new Work();
        $model->doc_type_id = $docType->id;
        $fileTypes = $docType->getFileTypes()->all();

        $files = [];

        foreach ($fileTypes as $fileType) {
            $m = new File();
            $m->_docType = $docType;
            $m->_fileType = $fileType;
            $files[$fileType->id] = $m;
        }


        if ($this->request->isPost) {
            $model->load($this->request->post());
            Model::loadMultiple($files, Yii::$app->request->post(),'File');
            foreach ($files as $k => $file) {
                $file->file = UploadedFile::getInstance($file, "[$k]file");
            }
            if ($model->validate() && Model::validateMultiple($files)) {
                $trans = Yii::$app->db->beginTransaction();
                try {
                    if ($model->save()) {
                        $success = true;
                        foreach ($files as $file) {
                            if(!$file->upload($model)){
                                $success = false;
                                Yii::$app->session->setFlash('error', "Error on save File");
                                break;
                            }
                        }
                        if ($success) {
                            $trans->commit();
                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                        $trans->rollBack();
                    }
                }catch (Exception $e) {
                    Yii::$app->session->setFlash('error', $e->getMessage());
                    $trans->rollBack();
                }

            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'files' => $files,
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
        $docType = $model->docType;

        $fileTypes = $docType->getFileTypes()->all();

        $files = [];

        foreach ($fileTypes as $fileType) {
            $m = new File();
            $m->_work = $model;
            $m->_docType = $docType;
            $m->_fileType = $fileType;
            $files[$fileType->id] = $m;
        }


        if ($this->request->isPost) {
            $model->load($this->request->post());
            Model::loadMultiple($files, Yii::$app->request->post(),'File');
            foreach ($files as $k => $file) {
                $file->_work = $model;
                $file->file = UploadedFile::getInstance($file, "[$k]file");
            }
            $trans = Yii::$app->db->beginTransaction();
            try {
                if ($model->save()) {
                    $success = true;
                    foreach ($files as $file) {
                        if(($file->file instanceof UploadedFile) == true && !$file->upload($model)){
                            $success = false;
                            Yii::$app->session->setFlash('error', "Error on save File");
                            break;
                        }
                    }
                    if ($success) {
                        $trans->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                    $trans->rollBack();
                }
            }catch (Exception $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
                $trans->rollBack();
            }
        } else {
            $model->loadDefaultValues();
        }

        /*if ($this->request->isPost) {
            $model->load($this->request->post());
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }*/

        return $this->render('update', [
            'model' => $model,
            'files' => $files,
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
        $work->is_visible = true;
        $work->save(false);

        return $this->redirect(['index']);
    }

    public function actionDocumentDelete($id, $doc_id)
    {
        $model = $this->findModel($id);
        $this->findDocument($doc_id)->delete();

        return $this->redirect(['update', 'id' => $model->id]);
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

    protected function findDocument($id)
    {
        if (($model = Document::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
