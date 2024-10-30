import { ref } from 'vue'

const isOpen = ref(false)

console.log("asdad")

export function useSidebar() {
  return {
    isOpen,
  }
}
