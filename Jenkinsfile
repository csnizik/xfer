def agentLabel
angentLabel = "PODS-DEV"



pipeline {

   agent {label agentLabel}
   environment {
        SLAVE_NODE = "PODS-DEV"
        ENV_NAME = "${env.BRANCH_NAME}"
        httpStatus = ""
    }
   stages {
      stage('Notify Bitbucket Status') {
        steps {
                bitbucketStatusNotify(buildState: 'INPROGRESS')
        }
      } 
      stage("Build Docker Deploy Image") {
        // We can build the "pods" image 
        when {
            anyOf {
                branch 'develop_refactor';
            }
        }
        //build deploy image for develop and release branch only
        steps {
          sh "docker build . -t pods:${ENV_NAME}"
          // Password and connection info coming from Greyworm Endo Jenkinsfile. Verify we can use the same ECR login info
          // sh "docker tag pods:${ENV_NAME} 766295386465.dkr.ecr.us-east-1.amazonaws.com/greyworm-endo-admin:${ENV_NAME}" 
          sh "docker run -p 85:80 pods:${ENV_NAME}"
        }
      }
      // stage("Push Docker Image to AWS ECR") {
      //   when {
      //       anyOf {
      //           branch 'develop';
      //           branch 'release'
      //       }
      //   }
      //   //push image for develop and release branch only
      //   steps {
      //     sh "aws ecr get-login-password --region us-east-1 --profile=cig | docker login --username AWS --password-stdin 766295386465.dkr.ecr.us-east-1.amazonaws.com"
      //     sh "docker push 766295386465.dkr.ecr.us-east-1.amazonaws.com/greyworm-endo-admin:${ENV_NAME}"
      //   }
      // }
      // stage("Rebuild Docker Container on Server") {
      //   when {
      //       anyOf {
      //           branch 'develop';
      //           branch 'release'
      //       }
      //   }
      //   //rebuild docker container for develop and release branch only
      //   steps {
      //     sh "sudo systemctl stop greyworm_endo_admin.service"
      //     sh "sudo systemctl start greyworm_endo_admin.service"
      //   }     
      // }
      // stage ('Check Service Health') {
        // Check Service Health for develop and release branch only
        // when {
        //     anyOf {
        //         branch 'develop';
        //         branch 'release'
        //     }
        // }
        // steps {                      
        //   script {
        //       sleep(10)
        //       httpStatus = sh(script: "curl --insecure -w '%{http_code}' $ppcUrl -o /dev/null --header 'Accept: application/json' ", returnStdout: true)
 
        //       if (httpStatus != "200" && httpStatus != "201" ) {
        //           echo "Service error with status code = ${httpStatus} when calling ${ppcUrl}"
        //           error("notify error")
        //       } else {
        //           echo "Service OK with status: ${httpStatus}"
        //       }
        //   }
        // }
 
      // }

   }
  post {
     // Let bitbucket know the final result
      success {
            bitbucketStatusNotify(buildState: 'SUCCESSFUL')
      }
      failure {
            bitbucketStatusNotify(buildState: 'FAILED')
      }
      always {
       echo "CI/CD pipeline finished"
      }
      cleanup{
        deleteDir()
      }
  }
}
