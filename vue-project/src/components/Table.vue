<script setup>
const props = defineProps({
  data: {
    type: Array,
    required: true
  },
  slv:{
    type:Array,
    required:true
  }
})
const emit = defineEmits(['edit'])
function edit(item){
  emit('edit', item)
}
</script>

<template>
  <table>
    <thead>
      <tr>
        <th scope="col" style="width:7%">Номер</th>
        <th scope="col" style="width:36%">ФИО</th>
        <th scope="col" style="width:30%">Компания</th>
        <th scope="col" style="width:20%">Группа</th>
        <th scope="col" style="width:7%">Присутствие</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(item, index) in data" :key="index" class="row" @click="edit(item)">
        <td>{{ item.id }}</td>
        <td>{{ item.fio }}</td>
        <td>{{ item.company }}</td>
        <td>{{ slv[item.group] }}</td>
        <td style="display:flex;justify-content:center;">
          <span :class="'dot '+(item.attend ? 'bg-green':'bg-red')"></span>
        </td>
      </tr>
    </tbody>

  </table>
</template>

<style scoped>
table {
  width: 100%;
  border-collapse: collapse;
}

td,
th {
  text-align: left;
}

th {
  font-family: "Open Sans", sans-serif;
  font-style: normal;
  font-size: 14px;
  font-weight: 300;
  color: #4E3000;
}

thead {
  border-bottom: 3px solid #E9E9E9;
}

td {
  font-family: "Open Sans", sans-serif;
  font-style: normal;
  font-weight: 400;
  font-size: 20px;
  line-height: 31px;
  color: #000000;
}

.row {
  cursor: pointer;
}

.row:hover {
  background: #E9E9E9;
}</style>
