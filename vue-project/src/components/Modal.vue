<script setup>
const show = defineModel('show')
const edit = defineModel('edit')
// const fio = defineModel('fio', { default: "" })
// const company = defineModel('company', { default: "" })
// const group = defineModel('group', { default: 0 })
// const attend = defineModel('attend', { default: false })
const props = defineProps({
  slv: Array,
  id:{
    type:Number,
    default:0
  },
  fio:{
    type:String,
    default:""
  },
  company:{
    type:String,
    default:""
  },
  group:{
    type:Number,
    default:-1
  },
  attend:{
    type:Boolean,
    default:false
  },
})

const emit = defineEmits(['submit', 'del'])

function closeModal() {
  show.value = false;
  edit.value = false;
}
function submit() {
  const id = props.id
  const fio=document.getElementById('fio').value
  const company=document.getElementById('company').value
  const group=parseInt(document.getElementById('group').value);
  const attend= document.getElementById('attend').checked
  emit('submit',{id,fio,company,group,attend})

}
function del() {
  emit('del',props.id)
}
</script>
<template>
  <div v-if="show" class="modal-shadow" @click.self="closeModal">
    <div class="modal">
      <div class="modal-close" @click="closeModal">&#10006;</div>
      <div class="row">
        <div class="text" style="width:20%;"> ФИО</div>
        <div style="width:40%;">
          <input id="fio" class="input" type="text" :value="fio">
        </div>
      </div>
      <div class="row">
        <div class="text" style="width:20%;"> Компания</div>
        <div style="width:40%;">
          <input id="company" class="input" type="text" :value="company">
        </div>
      </div>
      <div class="row">
        <div class="text" style="width:20%;"> Группа</div>
        <div style="width:40%;">
          <select id="group" class="select" :value="group">
            <option value="-1" v-if="!edit">Выбрать</option>
            <option v-for="(item, index) in slv" :value="index" :key="index">{{ item }}</option>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="text" style="width:20%;"> Присутствие</div>
        <div style="width:40%;">
          <input id="attend" class="checkbox" type="checkbox" :checked="attend">
        </div>
      </div>
      <slot name="error">
          
      </slot>
      <div class="row">
        <div style="padding-right:20px">
          <button v-if="edit" class="button-primary" @click="submit">Сохранить</button>
          <button v-else class="button-green" @click="submit">Добавить</button>

        </div>
        <div style="width:auto">
          <button v-if="edit" class="button-red" @click="del">Удалить</button>
          <button v-else class="button-grey" @click="closeModal">Закрыть</button>

        </div>
      </div>
    </div>
  </div>
</template>
<style scoped >
.row {
  padding-top: 10px;
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-shadow {
  position: absolute;
  top: 0;
  left: 0;
  min-height: 100%;
  width: 100%;
  background: rgba(0, 0, 0, 0.39);
}

.modal {
  background: #fff;
  border-radius: 8px;
  padding: 15px;
  min-width: 900px;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);

}

.text {
  font-family: "Open Sans", sans-serif;

  font-style: normal;
  font-weight: 700;
  font-size: 20px;
  text-align: left;
  color: #4E3000;
}

.modal-close {
  border-radius: 50%;
  color: #fff;
  background: #737373;
  display: flex;
  align-items: center;
  justify-content: center;
  position: absolute;
  top: 7px;
  right: 7px;
  width: 30px;
  height: 30px;
  cursor: pointer;
}

.modal-close:hover {
  background: #898989;

}
</style>