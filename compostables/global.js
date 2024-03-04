import axios from 'axios'
const url=import.meta.env.VITE_APP_URL+'vuecontroller';
export const controller=(controller,func,params={})=>{
    try {
    const data={};
     data.controller=controller.replaceAll('/','\\');
     data.function=func;
     data.params=params;
     return axios.post(url,data);
    } catch (error) {
        console.error(error)
    }
}
