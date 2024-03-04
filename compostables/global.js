import axios from 'axios'
export const controller=(controller,func,data={})=>{
    try {
     return axios.post(`/vuecontroller/${controller}/${func}`,data);
    } catch (error) {
        console.error(error)
    }
}
