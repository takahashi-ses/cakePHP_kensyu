<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Report Controller
 *
 * @property \App\Model\Table\ReportTable $Report
 *
 * @method \App\Model\Entity\Report[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReportController extends AppController
{   

    public function isAuthorized($user) {


        // var_dump($user);
        $action = $this->request->getParam("action");
        $userid = (int)$this->request->getParam("pass.0");
        if (in_array($action, ["edit", "view", "delete", "add"])) {
            if ($userid == $user["id"]) {
                return true;
            }
        }
        return parent::isAuthorized($user);
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users'],
        ];
        $report = $this->paginate($this->Report);

        $this->set(compact('report'));
    }

    /**
     * View method
     *
     * @param string|null $id Report id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $report = $this->Report->get($id, [
            'contain' => ['Users'],
        ]);

        $this->set('report', $report);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $report = $this->Report->newEntity();
        if ($this->request->is('post')) {
            $report = $this->Report->patchEntity($report, $this->request->getData());
            if ($this->Report->save($report)) {
                $this->Flash->success(__('The report has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The report could not be saved. Please, try again.'));
        }
        $users = $this->Report->Users->find('list', ['limit' => 200]);
        $this->set(compact('report', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Report id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $report = $this->Report->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $report = $this->Report->patchEntity($report, $this->request->getData());
            if ($this->Report->save($report)) {
                $this->Flash->success(__('The report has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The report could not be saved. Please, try again.'));
        }
        $users = $this->Report->Users->find('list', ['limit' => 200]);
        $this->set(compact('report', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Report id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $report = $this->Report->get($id);
        if ($this->Report->delete($report)) {
            $this->Flash->success(__('The report has been deleted.'));
        } else {
            $this->Flash->error(__('The report could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
