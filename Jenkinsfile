pipeline {
    agent any

    stages {
        stage('OWASP Dependency-Check Vulnerabilities') {
            steps {
                script {
                    dependencyCheck additionalArguments: '''
                        -o './'
                        -s './'
                        -f 'ALL'
                        --prettyPrint''', 
                        odcInstallation: 'OWASP Dependency-Check'
                    
                    dependencyCheckPublisher pattern: 'dependency-check-report.xml'
                }
            }
        }
    }

    post {
        always {
            // Archive the dependency check report for later review
            archiveArtifacts artifacts: 'dependency-check-report.xml', allowEmptyArchive: true
        }
        success {
            echo 'Dependency check completed successfully.'
        }
        failure {
            echo 'Dependency check failed.'
        }
    }
}
