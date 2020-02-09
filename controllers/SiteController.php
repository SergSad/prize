<?php

namespace app\controllers;

use app\models\PrizeBonus;
use app\models\PrizeMoney;
use app\models\PrizePhysical;
use app\models\Prizes;
use app\models\SignupForm;
use app\modules\prize\creators\BonusPrizeCreator;
use app\modules\prize\creators\MoneyPrizeCreator;
use app\modules\prize\creators\PhysicalPrizeCreator;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

	/**
	 * @return string|Response
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function actionSignup() {
		$model = new SignupForm();

		if ($model->load(Yii::$app->request->post())) {
			if ($user = $model->signup()) {
				if (Yii::$app->getUser()->login($user)) {
					return $this->goHome();
				}
			}
		}

		return $this->render('signup', [
			'model' => $model,
		]);
	}

	/**
	 * Сгенерировать подарок
	 *
	 * @author Sergey Sadovin <sadovin.serj@gmail.com>
	 */
	public function actionGenerate() {
		if (Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$types = Prizes::getAvailablePrizesType();
		$type  = $types[rand(0, count($types) - 1)];

		$prize = null;

		switch ($type) {
			case PrizePhysical::TYPE:
				$prize = new PhysicalPrizeCreator();
				break;
			case PrizeMoney::TYPE:
				$prize = new MoneyPrizeCreator();
				break;
			case PrizeBonus::TYPE:
				$prize = new BonusPrizeCreator();
				break;
		}

		if (null === $prize) {
			return $this->goHome();
		}

		$prize->generatePrize();

		return $this->goHome();
	}

}
