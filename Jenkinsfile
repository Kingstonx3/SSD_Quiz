pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                // Checkout the source code from the repository
                git url: 'https://github.com/Kingstonx3/SSD_Quiz.git', credentialsId: 'github_token'
            }
        }

        stage('Build and Test PHP') {
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
                        sh './vendor/bin/phpunit --configuration phpunit.xml --verbose'
                    }
                    post {
                        always {
                            junit testResults: 'logs/unitreport.xml', allowEmptyResults: true
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
                    --prettyPrint
                    --exclude **/dependency-check-report.xml
                    --exclude **/dependency-check-junit.xml
                    --exclude **/dependency-check-report.html
                    --exclude **/dependency-check-jenkins.html''', 
                    odcInstallation: 'OWASP Dependency-Check Vulnerabilities'
                
                dependencyCheckPublisher pattern: 'dependency-check-report.xml'
            }
        }

        stage('Build and Test Maven Project') {
            steps {
                sh '/var/jenkins_home/apache-maven-3.9.8/bin/mvn --batch-mode -V -U -e clean verify -Dsurefire.useFile=false -Dmaven.test.failure.ignore'
            }
        }

        stage('Analysis') {
            steps {
                sh '/var/jenkins_home/apache-maven-3.9.8/bin/mvn --batch-mode -V -U -e checkstyle:checkstyle pmd:pmd pmd:cpd findbugs:findbugs'
            }
        }

        stage('SonarQube Analysis') {
            environment {
                scannerHome = tool name: 'SonarQube', type: 'hudson.plugins.sonar.SonarRunnerInstallation'
            }
            steps {
                sh """
                    ${scannerHome}/bin/sonar-scanner \
                    -Dsonar.projectKey=OWASP \
                    -Dsonar.sources=. \
                    -Dsonar.exclusions=vendor/**,dependency-check-report.xml,dependency-check-junit.xml,dependency-check-report.html,dependency-check-jenkins.html,target/site/cpd.html \
                    -Dsonar.host.url=http://192.168.56.1:9000 \
                    -Dsonar.token=sqp_bbb52e965297eb405cee5cfbab178c9a262d0c7c
                """
            }
        }
    }

    post {
        always {
            // Archive the dependency check report and test results for later review
            archiveArtifacts artifacts: 'dependency-check-report.xml, logs/unitreport.xml', allowEmptyArchive: true
            junit testResults: '**/target/surefire-reports/TEST-*.xml', allowEmptyResults: true
            recordIssues enabledForFailure: true, tools: [mavenConsole(), java(), javaDoc()]
            recordIssues enabledForFailure: true, tool: checkStyle()
            recordIssues enabledForFailure: true, tool: findBugs(pattern: '**/target/findbugsXml.xml')
            recordIssues enabledForFailure: true, tool: cpd(pattern: '**/target/cpd.xml')
            recordIssues enabledForFailure: true, tool: pmdParser(pattern: '**/target/pmd.xml')
            recordIssues enabledForFailure: true, tool: sonarQube()
        }
        success {
            echo 'Pipeline completed successfully.'
        }
        failure {
            echo 'Pipeline failed.'
        }
    }
}
