<script>
export default {
  props: ['grade','student'],
  data() {
        return {
            klassLetters:[
                { value: "A", label: 'A' },
                { value: "B", label: 'B' },
                { value: "C", label: 'C' },
                { value: "D", label: 'D' },
                { value: "E", label: 'E' },
                { value: "F", label: 'F' },
                { value: "Z", label: 'Leave'}
            ]
        }
  }
}
</script>

<template>
    <a-radio-group v-model:value="student.promoteTo" option-type="button" :options="klassLetters" button-style="solid"/>
</template>