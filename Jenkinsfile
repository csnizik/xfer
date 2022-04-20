pipeline {
  agent {label "PODS-DEV"}

   stages {
      stage('Notify Bitbucket Status') {
        steps {
                bitbucketStatusNotify(buildState: 'INPROGRESS')
        }
      } 
      // stage("Build Docker Deploy Image") {
      //   // We can build the "pods" image 
      //   when {
      //       anyOf {
      //           branch 'develop_refactor';
      //       }
      //   }
      //   //build deploy image for develop and release branch only
      //   steps {
      //     sh "whoami"
      //     sh "docker build . -t pods"
      //   }
      // }
      
      stage("Reboot PODS"){
         when {
            anyOf {
                branch 'develop_refactor';
                branch 'develop';
            }
          }
          // Dockerfile needs to be modified to enable a service file maybe.
          // Right now Jenkins blocks on the process
          // Tried running this as a backgrounded task via "&" but that causes the build to fail silently
          // sh "docker run -d -p 85:80 pods"
          steps {
            sh "./run-pods.sh"
            sh "export PODS_CONTAINER_ID=$(docker ps -q -f name=pods-container -f status=running)"
            sh "docker exec $PODS_CONTAINER_ID vendor/bin/drush en field_layout"
            sh "docker exec $PODS_CONTAINER_ID vendor/bin/drush en cig_pods"
          }
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
