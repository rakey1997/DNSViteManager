<template>
    <makeFile :genFileData="genFileData"/>
    <DNSPage :queryForm="queryForm" />
</template>

<script>
    import DNSPage from "./components/dnsPage.vue";
    import makeFile from "./components/makeFile.vue";

    export default {
        name:'dnsRecurse',
        components:{DNSPage,makeFile},
        setup(){
            const queryForm={
                filterNameList:['递归服务器','缓存服务器']
            }
            const fileData={
                type:"server",
                fileNameList:{
                    fileName:"hosts-recursive-all.ini",
                    fileV6Name:"hosts-recursive-all-v6.ini",
                    whiteFileName:"whiteList-recursive.ini",
                    whiteV6FileName:"whiteList-recursive-v6.ini",
                    specialFileName:"hosts-recursive-special-all.ini",
                    specialV6FileName:"hosts-recursive-special-all-v6.ini",
                }
            }

            const genFileData={...queryForm, ...fileData}

            return{
                queryForm,
                genFileData
            }
        }
    }
</script>