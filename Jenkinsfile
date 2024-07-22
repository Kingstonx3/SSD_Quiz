pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                // Checkout the source code from the repository
                git url: 'https://github.com/Kingstonx3/SSD_Quiz.git', credentialsId: 'github_token'
            }
        }

        stage('Adjust Permissions') {
            steps {
                sh 'chmod +x ./vendor/bin/phpunit'
            }
        }

        stage('Build and Test') {
            agent {
                docker {
                    image 'composer:latest'
                    args '-v /var/run/docker.sock:/var/run/docker.sock' // Mount Docker socket if needed
                }
            }
            stages {
                stage('Build') {
                    steps {
                        sh 'composer install'
                    }
                }
                stage('Unit Test') {
                    steps {
                        sh 'sh ./vendor/bin/phpunit --log-junit logs/unitreport.xml tests'
                    }
                    post {
                        always {
                            junit 'logs/unitreport.xml'
                        }
                    }
                }
            }
        }

        stage('OWASP Dependency-Check Vulnerabilities') {
            steps {
                dependencyCheck additionalArguments: '''
                    -o './'
                    -s './'
                    -f 'ALL'
                    --prettyPrint''', 
                    odcInstallation: 'OWASP Dependency-Check Vulnerabilities'
                
                dependencyCheckPublisher pattern: 'dependency-check-report.xml'
            }
        }
    }

    post {
        always {
            // Archive the dependency check report and test results for later review
            archiveArtifacts artifacts: 'dependency-check-report.xml, logs/unitreport.xml', allowEmptyArchive: true
        }
        success {
            echo 'Pipeline completed successfully.'
        }
        failure {
            echo 'Pipeline failed.'
        }
    }
}
