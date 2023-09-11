<template>
    <makeFile :genFileData="genFileData"/>
    <DNSPage :queryForm="queryForm" />
</template>

<script>
    import DNSPage from "./components/dnsPage.vue";
    import makeFile from "./components/makeFile.vue";

    export default {
        name:'dnsAuth',
        components:{DNSPage,makeFile},
        setup(){
            const queryForm={
                filterNameList:['权威服务器']
            }
            const fileData={
                type:"server",
                fileNameList:{
                    fileName:"hosts-auth-all.ini",
                    fileV6Name:"hosts-auth-all-v6.ini",
                    whiteFileName:"whiteList-auth.ini",
                    whiteV6FileName:"whiteList-auth-v6.ini",
                    specialFileName:"hosts-auth-special-all.ini",
                    specialV6FileName:"hosts-auth-special-all-v6.ini",
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