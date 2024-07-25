<script setup>
import Table from './components/Table.vue'
import Modal from './components/Modal.vue'
import axios from "axios";
import { ref, onMounted } from 'vue'

const attend = ref(0)
const absence = ref(0)
const attendFilter = ref(-1)
const findName = ref("");
const findCompany = ref("");

const tableData = ref([]);

const modalShow = ref(false);
const editFlag = ref(false);
const edit = ref({
  id: 0,
  fio: '',
  company: '',
  group: -1,
  attend: false
});
const error = ref("");
const groupSlv = ['Прохожий', 'Клиент', 'Партнер']

function addModal() {
  modalShow.value = true;
  edit.value = {
    id: 0,
    fio: '',
    company: '',
    group: -1,
    attend: false
  };
}
function editModal(item) {
  edit.value = item
  editFlag.value = true;
  modalShow.value = true;
}

function add({ id, fio, company, group, attend }) {
  tableData.value.push({ id, fio, company, group, attend })
}
function save({id, fio, company, group, attend}) {
  tableData.value.map((item, index) => {
    if (item.id == id) {
      item.fio = fio;
      item.company = company;
      item.group = group;
      item.attend = attend;
      return;
    }
  })
}
async function init(){
  try {
    const res = await axios.post(import.meta.env.VITE_APP_URL + '/api/home', {
      fio:findName.value, company:findCompany.value, attend: attendFilter.value
    });
    if (res.data.ok) {
      tableData.value= res.data.tableData
      attend.value = res.data.attend
      absence.value=res.data.absence
    } 
  } catch (err) {
    console.log(err.message)
  }

}
async function submit({ id, fio, company, group, attend }) {
  edit.value = { id, fio, company, group, attend };
  error.value = ""
  try {
    const res = await axios.post(import.meta.env.VITE_APP_URL + '/api/submit', {
      data: edit.value,
    });
    if (res.data.ok) {
      if (id == 0) {
        add(edit.value)
      } else {
        save(edit.value)
      }
    } else {
      error.value = res.data.error
    }

  } catch (err) {
    error.value = err.message
  }
  if(!error.value){
    modalShow.value = false;
    editFlag.value = false;
  }
}
async function del(id) {
  try {
    const res = await axios.post(import.meta.env.VITE_APP_URL + '/api/del', {
      id: id,
    });
    if (res.data.ok) {
      tableData.value.map((item, index) => {
        if (item.id == id) {
          tableData.value.splice(index, 1);
          return;
        }
      })
    } else {
      error.value = res.data.error
    }
  } catch (error) {
    error.value = err.message

  }
  if(!error.value){
    modalShow.value = false;
    editFlag.value = false;
  }
}

onMounted(() => {
  init()
})
</script>

<template>
  <main>
    <div class="header">
      <img src="./assets/logo.svg" width="125" />
      <div class="col" style="width: 15%;">
        <input class="input" type="text" placeholder="Поиск по имени" v-model="findName" @keyup.enter="init">
      </div>
      <div class="col" style="width: 15%;">
        <input class="input" type="text" placeholder="Поиск по компании" v-model="findCompany" @keyup.enter="init">
      </div>
      <div class="col" style="width: 15%;">
        <button class="button-green" @click="addModal">Добавить</button>
      </div>
      <div style="width:auto;flex: 1;"></div>
      <div class="col" style="width: 10%;">
        <div class="text">Посетители</div>
        <div class="text"> <span style='color:#80BB00;'>{{ attend }}</span> / <span style='color:#EC5937;'>{{ absence
        }}</span></div>
      </div>
    </div>
    <div class="table">
      <Table :data="tableData" :slv="groupSlv" @edit="editModal" />
    </div>
    <Modal v-model:show="modalShow" v-model:edit="editFlag" :id="edit.id" :fio="edit.fio" :company="edit.company"
      :group="edit.group" :attend="edit.attend" :slv="groupSlv" @submit="submit" @del="del" >
     <template #error v-if="error">
      <div style="display:flex;justify-content:center;padding:6px;">
        <div class="error">
          {{ error }}
        </div>
      </div>
     </template>
    </Modal>

    <div class="footer">
      <div class="text" style="padding-left: 20px;">
        Фильтровать по:
      </div>
      <div :class="attendFilter == 0 ? 'selected' : 'filter'" @click="attendFilter = 0;init()">
        Отсутсвующим
      </div>
      <div :class="attendFilter == 1 ? 'selected' : 'filter'" @click="attendFilter = 1;init()">
        Присутствующим
      </div>
      <div :class="attendFilter == -1 ? 'selected' : 'filter'" @click="attendFilter = -1;init()">
        Без фильтра
      </div>
    </div>
  </main>
</template>

<style scoped>
.error{
  padding: 10px;
  color: #fff;
  border-radius: 10px;
  background: rgb(236, 81, 81);
  font-size: 14px;
  font-weight: 500;
  font-family: "Open Sans", sans-serif;
}
.header {
  display: flex;
  align-items: end;
}

.table {
  margin-top: 20px;
  flex-grow: 1;
  overflow-y: scroll;
  height: 100px;
}

.footer {
  display: flex;
  align-items: center;
  padding-bottom: 20px
}

.selected {
  margin-left: 20px;
  padding: 6px;
  color: #fff;
  border-radius: 10px;
  background: rgb(164, 164, 164);
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  font-family: "Open Sans", sans-serif;
}

.filter {
  padding: 6px;
  font-family: "Open Sans", sans-serif;
  font-style: normal;
  font-weight: 500;
  font-size: 14px;
  color: #4E3000;
  margin-left: 20px;
  cursor: pointer;
}

.filter:hover {
  border-radius: 10px;
  background: rgb(240, 240, 240);
}

main {
  padding: 8px 16px 8px 16px;
  display: flex;
  flex-direction: column;
  min-height: 98vh;
}

.text {
  font-family: "Open Sans", sans-serif;

  font-style: normal;
  font-weight: 700;
  font-size: 20px;
  line-height: 21px;
  /* identical to box height */
  text-align: right;
  color: #4E3000;
}
</style>
