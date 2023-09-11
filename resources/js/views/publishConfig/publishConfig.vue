<template>
    <h2 style="text-align: center;width: 90%;">{{$t('publish.publishTitle')}}</h2>
    <el-transfer
        :titles="titles"
        v-model="selectedValue"
        :data="data"
    />
    <el-form-item class="btn">
        <el-button type="primary" icon="UploadFilled" @click="publishConfigFiles" :disabled="(!selectedValue.length||flag)" >{{$t('publish.publishBtn')}}</el-button>
    </el-form-item>
</template>

<script>
    import { reactive, ref } from '@vue/reactivity'
    import { watch } from '@vue/runtime-core';
    import { publishFiles } from "../../api/dnsInfo";
    import {useStore} from "vuex";
    import { useI18n } from "vue-i18n";

    export default {
        name:'publishConfig',
        setup(){
            const store=useStore()
            const selectedValue = ref([])
            const flag=ref(store.getters.role!='admin')
            const {t}=useI18n()
            
            let titles=reactive(["",""])
            const data=ref([
                {
                    key:1,
                    label:"",
                    disabled:false
                },
                {
                    key:2,
                    label:"",
                    disabled:false
                },
            ])
            
            const publishConfigFiles=async ()=>{
                let sum=0
                selectedValue.value.forEach((item) => sum+=item)
                console.log(sum)

                const res=await publishFiles(sum)
                console.log(res);
            }

            watch(
                () => store.getters.lang,
                () => {
                    titles[0]=t('publish.toBeSelect')
                    titles[1]=t('publish.selected')
                    data.value[0].label=t('publish.monitorConfig')
                    data.value[1].label=t('publish.jsonConfig')
                },
                { deep: true,immediate:true }
            );
            
            return{
                selectedValue,
                data,
                flag,
                titles,
                publishConfigFiles
            }
        },
    }
</script>

<style lang="scss" scoped>
     /* 穿梭框外框高宽度 */
  :deep(.el-transfer-panel){
    width: 40%;
    height: 400px;
  }
  /* 穿梭框内部展示列表的高宽度 */
  :deep(.el-transfer-panel__list){
    height: 375px;
  }

  :deep(.el-form-item__content){
    // display:;    
    width:90%;
    justify-content: flex-start; 
    
    :deep(.el-button){
    display:block;margin:0 auto
    }
  }


</style>