def agentLabel = "PODS-DEV"

pipeline {

   environment {

    }
   stages {
      stage('Notify Bitbucket Status') {
        agent {label agentLabel}

        steps {
                bitbucketStatusNotify(buildState: 'INPROGRESS')
        }
      } 
      stage("Build Docker Deploy Image") {
        agent {label agentLabel}
        // We can build the "pods" image 
        when {
            anyOf {
                branch 'develop_refactor';
            }
        }
        //build deploy image for develop and release branch only
        steps {
          sh "docker build . -t pods"
        }
      }
      
      stage("Reboot PODS"){
         agent {label agentLabel}
          // Dockerfile needs to be modified to enable a service file maybe.
          // Right now Jenkins blocks on the process
          // Tried running this as a backgrounded task via "&" but that causes the build to fail silently
          // sh "docker run -d -p 85:80 pods"
          sh "./run-pods.sh"
      }
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
